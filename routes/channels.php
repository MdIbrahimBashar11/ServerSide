<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('project.{projectId}', function ($user, $projectId) {
    return \App\Domains\Projects\Models\Project::where('id', $projectId)
        ->where('user_id', $user->id)
        ->exists();
});
