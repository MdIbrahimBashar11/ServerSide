<?php

namespace App\Domains\EventForwarding\Platforms\Facebook;

use Illuminate\Support\Facades\Http;
use App\Domains\Projects\Models\Event;
use App\Domains\Projects\Models\Destination;
use App\Domains\Projects\Models\EventDeliveryLog;
use Illuminate\Support\Facades\Log;

class FacebookService
{
    /**
     * Send event to Facebook Conversion API
     */
    public function sendEvent(Event $event, Destination $destination)
    {
        $pixelId = $destination->dataset_id;
        $accessToken = $destination->access_token;
        $url = "https://graph.facebook.com/v19.0/{$pixelId}/events";

        $payload = [
            'data' => [
                $this->formatEvent($event)
            ]
        ];

        // Send Request to Facebook CAPI
        $response = Http::withToken($accessToken)->post($url, $payload);

        // Handle error logging and response capture
        $this->logDelivery($event, $destination, $response, $payload);

        return $response;
    }

    protected function formatEvent(Event $event)
    {
        $userData = $event->user_data ?? [];
        $customData = $event->custom_data ?? [];

        // Hashing Personal Identifying Information (SHA-256 required)
        $hashedUserData = [];
        if (!empty($userData['email'])) {
            $hashedUserData['em'] = hash('sha256', strtolower(trim($userData['email'])));
        }
        if (!empty($userData['phone'])) {
            $hashedUserData['ph'] = hash('sha256', preg_replace('/\D/', '', $userData['phone']));
        }
        if (!empty($userData['client_ip_address'])) {
            $hashedUserData['client_ip_address'] = $userData['client_ip_address'];
        }
        if (!empty($userData['client_user_agent'])) {
            $hashedUserData['client_user_agent'] = $userData['client_user_agent'];
        }

        // FBP and FBC advanced matching support
        if (!empty($userData['fbp'])) { $hashedUserData['fbp'] = $userData['fbp']; }
        if (!empty($userData['fbc'])) { $hashedUserData['fbc'] = $userData['fbc']; }

        $formatted = [
            'event_name' => strtolower($event->event_name) === 'purchase' ? 'Purchase' : $event->event_name,
            'event_time' => $event->event_time ? $event->event_time->timestamp : time(),
            'event_id' => $event->event_id, // Mandatory Deduplication ID
            'action_source' => 'website',
            'user_data' => $hashedUserData,
            'custom_data' => []
        ];

        // Specific rules for Purchase events
        if (strtolower($event->event_name) === 'purchase') {
            $formatted['custom_data']['value'] = $customData['value'] ?? 0;
            $formatted['custom_data']['currency'] = $customData['currency'] ?? 'USD';
        }

        return $formatted;
    }

    protected function logDelivery(Event $event, Destination $destination, $response, $payload)
    {
        $status = $response->successful() ? 'success' : 'failed';

        if ($status === 'failed') {
            Log::error("Facebook CAPI Error for Event {$event->event_id}", ['response' => $response->json()]);
        }

        EventDeliveryLog::create([
            'event_id' => $event->id,
            'destination_id' => $destination->id,
            'status' => $status,
            'request_payload' => $payload,
            'response_code' => $response->status(),
            'response_body' => $response->body(),
        ]);
        
        // Trigger Laravel automatic retry for Server Errors (Rate limits etc)
        if ($status === 'failed' && $response->serverError()) {
            throw new \Exception("Facebook API Server Error"); 
        }
    }
}
