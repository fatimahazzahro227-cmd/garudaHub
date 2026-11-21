<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $users = User::withCount(['projects', 'bids'])->orderBy('id')->get();
        $projects = Project::with('client')->withCount('bids')->latest('id')->get();

        return view('admin.index', compact('users', 'projects'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('err', 'Anda tidak dapat mengubah peran akun sendiri.');
        }

        $data = $request->validate(['role' => ['required', Rule::in(User::ROLES)]]);
        $user->forceFill(['role' => $data['role']])->save();

        return back()->with('ok', 'Peran user diubah.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('err', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('ok', 'User dihapus.');
    }
}
