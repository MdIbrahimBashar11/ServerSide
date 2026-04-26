<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatewaySetting extends Model
{
    protected $fillable = [
        'gateway_name',
        'client_id',
        'client_secret',
        'webhook_secret',
        'additional_config',
        'is_active',
    ];

    protected $casts = [
        'additional_config' => 'array',
        'is_active' => 'boolean',
    ];
}
