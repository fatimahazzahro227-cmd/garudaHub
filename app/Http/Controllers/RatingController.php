<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Project;
use App\Models\Rating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class RatingController extends Controller
{
    public function store(RatingRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('rate', $project);

        $rating = new Rating($request->validated());
        $rating->project_id = $project->id;
        $rating->client_id = $request->user()->id;
        $rating->developer_id = $project->winningBid->developer_id;
        $rating->save();

        return redirect()->route('projects.show', $project)
            ->with('ok', 'Rating developer berhasil dikirim.');
    }
}