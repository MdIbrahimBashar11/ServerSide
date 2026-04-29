<?php

namespace App\Domains\EventForwarding\Platforms\TikTok;

use Illuminate\Support\Facades\Http;
use App\Domains\Projects\Models\Event;
use App\Domains\Projects\Models\Destination;
use App\Domains\Projects\Models\EventDeliveryLog;
use Illuminate\Support\Facades\Log;

class TikTokService
{
    /**
     * Send event to TikTok Events API
     */
    public function sendEvent(Event $event, Destination $destination)
    {
        $pixelId = $destination->dataset_id;
        $accessToken = $destination->access_token;
        $url = "https://business-api.tiktok.com/open_api/v1.3/event/track/";

        $payload = [
            'pixel_code' => $pixelId,
            'event' => $event->event_name,
            'event_id' => $event->event_id,
            'timestamp' => $event->event_time ? $event->event_time->timestamp : time(),
            'context' => [
                'ad' => [
                    'callback' => $event->user_data['tt_callback'] ?? null,
                ],
                'user' => $this->formatUserData($event->user_data ?? []),
                'page' => [
                    'url' => $event->user_data['page_url'] ?? null,
                    'referrer' => $event->user_data['referrer'] ?? null,
                ],
            ],
            'properties' => $this->formatProperties($event->custom_data ?? []),
        ];

        // Send Request to TikTok API
        $response = Http::withHeaders([
            'Access-Token' => $accessToken,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        // Handle error logging and response capture
        $this->logDelivery($event, $destination, $response, $payload);

        return $response;
    }

    protected function formatUserData(array $userData)
    {
        $formatted = [];
        
        if (!empty($userData['email'])) {
            $formatted['email'] = hash('sha256', strtolower(trim($userData['email'])));
        }
        if (!empty($userData['phone'])) {
            $formatted['phone_number'] = hash('sha256', preg_replace('/\D/', '', $userData['phone']));
        }
        if (!empty($userData['client_ip_address'])) {
            $formatted['ip'] = $userData['client_ip_address'];
        }
        if (!empty($userData['client_user_agent'])) {
            $formatted['user_agent'] = $userData['client_user_agent'];
        }
        if (!empty($userData['tt_pixel_id'])) {
            $formatted['ttp'] = $userData['tt_pixel_id'];
        }

        return $formatted;
    }

    protected function formatProperties(array $customData)
    {
        $properties = [];
        if (!empty($customData['value'])) $properties['value'] = (float)$customData['value'];
        if (!empty($customData['currency'])) $properties['currency'] = $customData['currency'];
        if (!empty($customData['content_type'])) $properties['content_type'] = $customData['content_type'];
        if (!empty($customData['content_id'])) $properties['content_id'] = $customData['content_id'];
        
        return $properties;
    }

    protected function logDelivery(Event $event, Destination $destination, $response, $payload)
    {
        $status = $response->successful() ? 'success' : 'failed';

        if ($status === 'failed') {
            Log::error("TikTok API Error for Event {$event->event_id}", ['response' => $response->json()]);
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
            throw new \Exception("TikTok API Server Error"); 
        }
    }
}
