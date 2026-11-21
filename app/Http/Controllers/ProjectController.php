<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProjectController extends Controller
{
 
    public function index(Request $request): View
    {
        $q = mb_substr(trim((string) $request->query('q', '')), 0, 100);

        $projects = Project::with('client')
            ->withCount('bids')
            ->search($q)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('projects.index', compact('projects', 'q'));
    }

    public function show(Request $request, Project $project): View
    {
        $project->load([
            'client',
            'bids' => fn ($q) => $q->with('developer')->orderByDesc('is_winner')->orderBy('harga')->orderBy('id'),
        ]);

        $user = $request->user();
        $myBid = $user ? $project->bids->firstWhere('developer_id', $user->id) : null;

        return view('projects.show', compact('project', 'myBid'));
    }

    public function create(): View
    {
        Gate::authorize('create', Project::class);

        return view('projects.create', ['project' => new Project]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        Gate::authorize('create', Project::class);

        $project = request->user()->projects()->create(request->validated());

        return redirect()->route('projects.show', $project)->with('ok', 'Proyek berhasil dipasang.');
    }

    public function edit(Project $project): View
    {
        Gate::authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        project->update(request->validated());

        return redirect()->route('projects.show', $project)->with('ok', 'Proyek berhasil diperbarui.');
    }
   
    public function destroy(Request $request, Project $project): RedirectResponse
    {
        Gate::authorize('delete', $project);

        $project->delete();

        $to = $request->user()->isAdmin() ? route('admin.index') : route('dashboard');

        return redirect($to)->with('ok', 'Proyek dihapus.');
    }
}
