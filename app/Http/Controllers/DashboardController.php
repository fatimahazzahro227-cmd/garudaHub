<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.index');
        }

        if ($user->role === 'klien') {
            $projects = $user->projects()->withCount('bids')->latest('id')->get();

            return view('dashboard.klien', compact('projects'));
        }

        $bids = $user->bids()->with('project.client')->latest('id')->get();

        return view('dashboard.developer', compact('bids'));
    }
}
