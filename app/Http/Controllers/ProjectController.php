<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
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

    public function show(Project $project): View
    {
        $project->load([
            'client',
            'bids' => fn ($q) => $q->with('developer')->orderByDesc('is_winner')->orderBy('harga')->orderBy('id'),
        ]);

        return view('projects.show', compact('project'));
    }
}
