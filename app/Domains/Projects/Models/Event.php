<?php

namespace App\Domains\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = ['project_id', 'event_name', 'event_time', 'user_data', 'custom_data', 'source'];

    protected $casts = [
        'user_data' => 'array',
        'custom_data' => 'array',
        'event_time' => 'datetime',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\EventFactory::new();
    }
}
