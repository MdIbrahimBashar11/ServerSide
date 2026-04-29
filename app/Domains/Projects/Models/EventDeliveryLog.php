<?php

namespace App\Domains\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EventDeliveryLog extends Model
{
    use HasUuids;

    protected $fillable = ['event_id', 'destination_id', 'status', 'request_payload', 'response_code', 'response_body'];

    protected $casts = [
        'request_payload' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
