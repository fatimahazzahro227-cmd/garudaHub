<?php

namespace Tests\Feature;

use App\Models\Bid;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GarudaHubTest extends TestCase
{
    use RefreshDatabase;

    private function projectData(array $override = []): array
    {
        return array_merge([
            'judul' => 'Website Kantin',
            'deskripsi' => 'Pemesanan menu kantin kampus.',
            'kategori' => 'Web',
            'budget_min' => 1000000,
            'budget_max' => 2000000,
            'batas_lelang' => now()->addWeek()->toDateString(),
        ], $override);
    }

    public function test_relasi_eloquent(): void
    {
        $bid = Bid::factory()->create();

        $this->assertTrue($bid->project->client->is($bid->project->client()->first()));
        $this->assertCount(1, $bid->project->bids);
        $this->assertCount(1, $bid->developer->bids);
        $this->assertCount(1, $bid->project->client->projects);
    }

    public function test_daftar_tidak_bisa_memilih_peran_admin(): void
    {
        $this->post('/register', ['nama' => 'X', 'email' => 'x@example.com', 'password' => 'rahasia1', 'role' => 'admin'])
            ->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'x@example.com']);
    }

    public function test_daftar_dan_login(): void
    {
        $this->post('/register', ['nama' => 'Rani', 'email' => 'Rani@Example.com', 'password' => 'rahasia1', 'role' => 'developer'])
            ->assertRedirect('/login');
        $this->assertDatabaseHas('users', ['email' => 'rani@example.com', 'role' => 'developer']);

        $this->post('/login', ['email' => 'rani@example.com', 'password' => 'rahasia1'])->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_klien_crud_proyek(): void
    {
        $klien = User::factory()->klien()->create();

        $this->actingAs($klien)->post('/projects', $this->projectData(['client_id' => 999]))->assertRedirect();
        $project = Project::firstOrFail();
        $this->assertSame($klien->id, $project->client_id); // client_id dari sesi, bukan form

        $this->get('/')->assertOk()->assertSee('Website Kantin');
        $this->get("/projects/{$project->id}")->assertOk()->assertSee('Pemesanan menu kantin kampus.');

        $this->put("/projects/{$project->id}", $this->projectData(['judul' => 'Website Kantin v2']))->assertRedirect();
        $this->assertSame('Website Kantin v2', $project->fresh()->judul);

        $this->delete("/projects/{$project->id}")->assertRedirect('/dashboard');
        $this->assertModelMissing($project);
    }

    public function test_validasi_budget_dan_batas(): void
    {
        $klien = User::factory()->klien()->create();

        $this->actingAs($klien)
            ->post('/projects', $this->projectData(['budget_max' => 10, 'batas_lelang' => '2000-01-01']))
            ->assertSessionHasErrors(['budget_max', 'batas_lelang']);
    }

    public function test_klien_lain_tidak_bisa_edit_proyek(): void
    {
        $project = Project::factory()->create();
        $lain = User::factory()->klien()->create();

        $this->actingAs($lain)->put("/projects/{$project->id}", $this->projectData())->assertForbidden();
        $this->actingAs($lain)->delete("/projects/{$project->id}")->assertForbidden();
    }

    public function test_developer_crud_penawaran(): void
    {
        $project = Project::factory()->create();
        $dev = User::factory()->developer()->create();
        $bid = ['harga' => 1500000, 'durasi' => 14, 'proposal' => 'Laravel + MySQL'];

        $this->actingAs($dev)->post("/projects/{$project->id}/bids", $bid)->assertRedirect();
        $this->post("/projects/{$project->id}/bids", $bid)->assertSessionHas('err'); 
        $this->assertSame(1, Bid::count());

        $model = Bid::firstOrFail();
        $this->put("/bids/{$model->id}", ['harga' => 1200000] + $bid)->assertRedirect();
        $this->assertSame(1200000, $model->fresh()->harga);

        $this->delete("/bids/{$model->id}")->assertRedirect();
        $this->assertSame(0, Bid::count());
    }

    public function test_klien_tidak_bisa_menawar(): void
    {
        $project = Project::factory()->create();

        $this->actingAs($project->client)
            ->post("/projects/{$project->id}/bids", ['harga' => 1, 'durasi' => 1, 'proposal' => 'x'])
            ->assertForbidden();
    }

    public function test_pilih_pemenang_menutup_lelang(): void
    {
        $project = Project::factory()->create();
        $bids = Bid::factory()->count(2)->for($project)->create();

        $this->actingAs($project->client)->post("/projects/{$project->id}/bids/{$bids[0]->id}/pick")->assertRedirect();

        $this->assertTrue($bids[0]->fresh()->is_winner);
        $this->assertSame(Project::STATUS_CLOSED, $project->fresh()->status);

        $this->post("/projects/{$project->id}/bids/{$bids[1]->id}/pick")->assertForbidden();
        $this->assertFalse($bids[1]->fresh()->is_winner);
    }

    public function test_admin_ubah_peran_dan_hapus_user_cascade(): void
    {
        $admin = User::factory()->admin()->create();
        $bid = Bid::factory()->create();
        $klien = $bid->project->client;

        $this->actingAs($admin)->patch("/admin/users/{$bid->developer_id}/role", ['role' => 'klien'])->assertRedirect();
        $this->assertSame('klien', $bid->developer->fresh()->role);

        $this->delete("/admin/users/{$klien->id}")->assertRedirect();
        $this->assertModelMissing($bid->project);
        $this->assertModelMissing($bid); // ikut terhapus lewat FK cascade

        $this->delete("/admin/users/{$admin->id}")->assertSessionHas('err');
        $this->assertModelExists($admin);
    }

    public function test_non_admin_ditolak_dari_admin(): void
    {
        $this->actingAs(User::factory()->developer()->create())->get('/admin')->assertForbidden();
    }
}

