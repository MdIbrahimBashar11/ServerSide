<?php

namespace App\Domains\EventForwarding\Platforms\Facebook;

use Illuminate\Support\Facades\Http;
use App\Domains\Projects\Models\Event;
use App\Domains\Projects\Models\Destination;
use App\Domains\Projects\Models\EventDeliveryLog;
use Illuminate\Support\Facades\Log;

class FacebookService
{
    public function sendEvent(Event $event, Destination $destination)
    {
        $pixelId = $destination->dataset_id;
        $accessToken = $destination->access_token;
        $url = "https://graph.facebook.com/v19.0/{$pixelId}/events";

        $formattedEvent = $this->formatEvent($event);
        
        $payload = [
            'data' => [
                $formattedEvent
            ]
        ];

        // Support for Facebook Test Event Code (CRITICAL for testing in Events Manager)
        if (!empty($event->custom_data['test_event_code'])) {
            $payload['test_event_code'] = $event->custom_data['test_event_code'];
        }

        // FULL LOGGING for debugging
        Log::info("Sending FB CAPI Event: {$event->event_name}", [
            'pixel_id' => $pixelId,
            'payload' => $payload
        ]);

        // Send Request to Facebook CAPI
        try {
            $response = Http::withToken($accessToken)->post($url, $payload);
            
            Log::info("FB CAPI Response", [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            $this->logDelivery($event, $destination, $response, $payload);

            return $response;
        } catch (\Exception $e) {
            Log::error("FB CAPI Request Exception: " . $e->getMessage());
            throw $e;
        }
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

        // External ID for better matching
        if (!empty($event->user_id)) {
            $hashedUserData['external_id'] = hash('sha256', $event->user_id);
        }

        $formatted = [
            'event_name' => $this->mapEventName($event->event_name),
            'event_time' => $event->event_time ? $event->event_time->timestamp : time(),
            'event_id' => $event->event_id, // Mandatory Deduplication ID
            'action_source' => 'website',
            'event_source_url' => $userData['page_url'] ?? null,
            'user_data' => $hashedUserData,
            'custom_data' => $this->formatCustomData($customData)
        ];

        return $formatted;
    }

    protected function formatCustomData(array $customData)
    {
        $formatted = [];
        
        // Standard E-commerce parameters
        if (isset($customData['value'])) $formatted['value'] = (float) $customData['value'];
        if (isset($customData['currency'])) $formatted['currency'] = strtoupper($customData['currency']);
        if (isset($customData['content_ids'])) $formatted['content_ids'] = (array) $customData['content_ids'];
        if (isset($customData['content_name'])) $formatted['content_name'] = $customData['content_name'];
        if (isset($customData['content_type'])) $formatted['content_type'] = $customData['content_type'];
        if (isset($customData['content_category'])) $formatted['content_category'] = $customData['content_category'];
        if (isset($customData['num_items'])) $formatted['num_items'] = (int) $customData['num_items'];
        if (isset($customData['search_string'])) $formatted['search_string'] = $customData['search_string'];

        // Pass through any other custom data
        return array_merge($customData, $formatted);
    }

    protected function mapEventName($name)
    {
        $map = [
            'PageView' => 'PageView',
            'AddToCart' => 'AddToCart',
            'Purchase' => 'Purchase',
            'Lead' => 'Lead',
            'ViewContent' => 'ViewContent',
            'InitiateCheckout' => 'InitiateCheckout',
            'AddToWishlist' => 'AddToWishlist',
            'CompleteRegistration' => 'CompleteRegistration',
            'Contact' => 'Contact',
            'CustomizeProduct' => 'CustomizeProduct',
            'Donate' => 'Donate',
            'FindLocation' => 'FindLocation',
            'Schedule' => 'Schedule',
            'Search' => 'Search',
            'StartTrial' => 'StartTrial',
            'SubmitApplication' => 'SubmitApplication',
            'Subscribe' => 'Subscribe'
        ];
        return $map[$name] ?? $name;
    }

    protected function logDelivery(Event $event, Destination $destination, $response, $payload)
    {
        $status = $response->successful() ? 'success' : 'failed';

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
            throw new \Exception("Facebook API Server Error: " . $response->body()); 
        }
    }
}
