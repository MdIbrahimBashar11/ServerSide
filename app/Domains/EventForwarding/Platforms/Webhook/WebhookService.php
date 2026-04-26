<?php

namespace App\Domains\EventForwarding\Platforms\Webhook;

use Illuminate\Support\Facades\Http;
use App\Domains\Projects\Models\Event;
use App\Domains\Projects\Models\Destination;
use App\Domains\Projects\Models\EventDeliveryLog;

class WebhookService
{
    public function sendEvent(Event $event, Destination $destination): bool
    {
        // For webhook architecture, dataset_id stores the raw target URL
        $targetUrl = $destination->dataset_id; 

        if (!$targetUrl) {
            return false;
        }

        $payload = [
            'tracking_id' => $event->project->tracking_id ?? null,
            'event_id' => $event->event_id,
            'event_name' => $event->event_name,
            'trigger_time' => $event->event_time,
            'user' => $event->user_data,
            'parameters' => $event->custom_data,
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'User-Agent' => 'ServerTrack-Edge-Node/1.0'
        ];

        if (!empty($destination->access_token)) {
            $headers['Authorization'] = 'Bearer ' . $destination->access_token;
            // Also supply a generic signature header for custom webhook implementations
            $headers['X-ServerTrack-Signature'] = hash_hmac('sha256', json_encode($payload), $destination->access_token);
        }

        $response = Http::withHeaders($headers)
            ->timeout(15) 
            ->post($targetUrl, $payload);

        EventDeliveryLog::create([
            'event_id' => $event->id,
            'destination_id' => $destination->id,
            'status' => $response->successful() ? 'success' : 'failed',
            'request_payload' => $payload,
            'response_code' => $response->status(),
            'response_body' => substr($response->body(), 0, 1000), // Trim massive response payloads for DB health
        ]);

        if (!$response->successful()) {
            throw new \Exception("External CRM Webhook Failed. Target: {$targetUrl} | Status: {$response->status()}");
        }

        return true;
    }
}
