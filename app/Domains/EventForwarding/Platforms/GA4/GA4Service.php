<?php

namespace App\Domains\EventForwarding\Platforms\GA4;

use Illuminate\Support\Facades\Http;
use App\Domains\Projects\Models\Event;
use App\Domains\Projects\Models\Destination;
use App\Domains\Projects\Models\EventDeliveryLog;
use Illuminate\Support\Facades\Log;

class GA4Service
{
    /**
     * Send event to Google Analytics 4 Measurement Protocol
     */
    public function sendEvent(Event $event, Destination $destination)
    {
        $measurementId = $destination->dataset_id;
        $apiSecret = $destination->access_token; // Stored in access_token column
        $url = "https://www.google-analytics.com/mp/collect?measurement_id={$measurementId}&api_secret={$apiSecret}";

        $userData = $event->user_data ?? [];
        $clientId = $userData['client_id'] ?? uniqid(); // client_id is strictly required per MP spec

        $payload = [
            'client_id' => $clientId,
            'events' => [
                [
                    'name' => strtolower($event->event_name) === 'purchase' ? 'purchase' : $event->event_name,
                    'params' => array_merge([
                        'event_id' => $event->event_id, // Recommended for QA/Deduplication
                    ], $event->custom_data ?? [])
                ]
            ]
        ];

        // Send Request to GA4
        $response = Http::post($url, $payload);

        // Handle error logging
        $this->logDelivery($event, $destination, $response, $payload);

        return $response;
    }

    protected function logDelivery(Event $event, Destination $destination, $response, $payload)
    {
        // GA4 MP typically returns 204 No Content for successful receipt
        $status = $response->successful() ? 'success' : 'failed';

        if ($status === 'failed') {
            Log::error("GA4 MP Error for Event {$event->event_id}", ['response' => $response->json()]);
        }

        EventDeliveryLog::create([
            'event_id' => $event->id,
            'destination_id' => $destination->id,
            'status' => $status,
            'request_payload' => $payload,
            'response_code' => $response->status(),
            'response_body' => $response->body(),
        ]);
        
        if ($status === 'failed' && $response->serverError()) {
            throw new \Exception("GA4 API Server Error"); 
        }
    }
}
