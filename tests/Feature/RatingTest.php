<?php

namespace Tests\Feature;

use App\Models\Bid;
use App\Models\Project;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    private function proyek_selesai(): Project
    {
        $project = Project::factory()->create(['status' => Project::STATUS_CLOSED]);
        Bid::factory()->for($project)->create(['is_winner' => true]);

        return $project;
    }

    public function test_klien_pemilik_bisa_memberi_rating(): void
    {
        $project = $this->proyek_selesai();

        $this->actingAs($project->client)
            ->post("/projects/{$project->id}/rating", ['skor' => 5, 'ulasan' => 'Hasil kerja rapi dan tepat waktu.'])
            ->assertRedirect(route('projects.show', $project))
            ->assertSessionHas('ok');

        $this->assertDatabaseHas('ratings', [
            'project_id' => $project->id,
            'client_id' => $project->client_id,
            'developer_id' => $project->winningBid->developer_id,
            'skor' => 5,
        ]);
    }

    public function test_developer_yang_dinilai_tidak_bisa_dipalsukan_lewat_form(): void
    {
        $project = $this->proyek_selesai();
        $orang_lain = User::factory()->developer()->create();

        $this->actingAs($project->client)
            ->post("/projects/{$project->id}/rating", [
                'skor' => 3,
                'developer_id' => $orang_lain->id,
                'client_id' => $orang_lain->id,
            ])
            ->assertRedirect();

        $rating = Rating::firstOrFail();
        $this->assertSame($project->winningBid->developer_id, $rating->developer_id); 
        $this->assertSame($project->client_id, $rating->client_id);                    
    }

    public function test_rating_hanya_bisa_sekali_per_proyek(): void
    {
        $project = $this->proyek_selesai();

        $this->actingAs($project->client)->post("/projects/{$project->id}/rating", ['skor' => 5])->assertRedirect();
        $this->post("/projects/{$project->id}/rating", ['skor' => 1])->assertForbidden();

        $this->assertSame(1, Rating::count());
        $this->assertSame(5, Rating::first()->skor);
    }

    public function test_klien_lain_tidak_bisa_memberi_rating(): void
    {
        $project = $this->proyek_selesai();
        $lain = User::factory()->klien()->create();

        $this->actingAs($lain)->post("/projects/{$project->id}/rating", ['skor' => 5])->assertForbidden();

        $this->assertSame(0, Rating::count());
    }

    public function test_developer_tidak_bisa_memberi_rating(): void
    {
        $project = $this->proyek_selesai();

        $this->actingAs($project->winningBid->developer)
            ->post("/projects/{$project->id}/rating", ['skor' => 5])
            ->assertForbidden();

        $this->assertSame(0, Rating::count());
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $project = $this->proyek_selesai();

        $this->post("/projects/{$project->id}/rating", ['skor' => 5])->assertRedirect(route('login'));

        $this->assertSame(0, Rating::count());
    }

    public function test_proyek_belum_ditutup_atau_tanpa_pemenang_tidak_bisa_dirating(): void
    {
        $terbuka = Project::factory()->create();
        Bid::factory()->for($terbuka)->create();

        $this->actingAs($terbuka->client)->post("/projects/{$terbuka->id}/rating", ['skor' => 5])->assertForbidden();

        $tanpa_pemenang = Project::factory()->create(['status' => Project::STATUS_CLOSED]);

        $this->actingAs($tanpa_pemenang->client)->post("/projects/{$tanpa_pemenang->id}/rating", ['skor' => 5])->assertForbidden();

        $this->assertSame(0, Rating::count());
    }

    public function test_validasi_skor_dan_ulasan(): void
    {
        $project = $this->proyek_selesai();
        $this->actingAs($project->client);

        foreach ([null, 0, 6, 'abc', 3.5] as $skor) {
            $this->post("/projects/{$project->id}/rating", ['skor' => $skor])->assertSessionHasErrors('skor');
        }

        $this->post("/projects/{$project->id}/rating", ['skor' => 4, 'ulasan' => str_repeat('a', 501)])
            ->assertSessionHasErrors('ulasan');

        $this->assertSame(0, Rating::count());
    }

    public function test_form_rating_hanya_muncul_untuk_klien_pemilik(): void
    {
        $project = $this->proyek_selesai();
        $lain = User::factory()->klien()->create();

        $this->actingAs($project->client)->get("/projects/{$project->id}")->assertOk()->assertSee('Kirim rating');
        $this->actingAs($lain)->get("/projects/{$project->id}")->assertOk()->assertDontSee('Kirim rating');
        auth()->logout();
        $this->get("/projects/{$project->id}")->assertOk()->assertDontSee('Kirim rating');

        $this->actingAs($project->client)->post("/projects/{$project->id}/rating", ['skor' => 4]);
        $this->get("/projects/{$project->id}")->assertDontSee('Kirim rating');
    }

    public function test_rata_rata_rating_tampil_di_halaman_proyek(): void
    {
        $project = $this->proyek_selesai();

        $this->actingAs($project->client)->post("/projects/{$project->id}/rating", ['skor' => 4]);

        $this->get("/projects/{$project->id}")->assertOk()->assertSee('★ 4.0 (1)');
    }

    public function test_ulasan_di_escape_sehingga_aman_dari_xss(): void
    {
        $project = $this->proyek_selesai();
        $serangan = '<script>alert(1)</script>';

        $this->actingAs($project->client)->post("/projects/{$project->id}/rating", ['skor' => 5, 'ulasan' => $serangan]);

        $this->get("/projects/{$project->id}")
            ->assertOk()
            ->assertDontSee($serangan, false)                              
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);   
    }
}
