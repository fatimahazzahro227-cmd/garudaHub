<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'klien';
    }

    public function update(User $user, Project $project): bool
    {
        return $this->owns($user, $project) && $project->status === Project::STATUS_OPEN;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->isAdmin() || $this->update($user, $project);
    }

    public function pickWinner(User $user, Project $project): bool
    {
        return $this->update($user, $project);
    }

    private function owns(User $user, Project $project): bool
    {
        return $user->role === 'klien' && $project->client_id === $user->id;
    }
}
