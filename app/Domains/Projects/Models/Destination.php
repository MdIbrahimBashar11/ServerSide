<?php

namespace App\Domains\Projects\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['project_id', 'platform', 'dataset_id', 'access_token', 'settings', 'is_active'];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];
}
