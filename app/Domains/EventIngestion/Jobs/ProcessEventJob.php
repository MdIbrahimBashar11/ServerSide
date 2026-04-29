<?php

namespace App\Domains\EventIngestion\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Domains\Projects\Models\Event;

class ProcessEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Exponential backoff retry system
    public $tries = 5;

    public function backoff(): array
    {
        // Wait 10s, then 30s, then 1m, then 5m between retries if Meta API throws 429
        return [10, 30, 60, 300];
    }

    public function __construct(public Event $event)
    {
    }

    public function handle(): void
    {
        $destinations = $this->event->project?->destinations()->where('is_active', true)->get() ?? collect();
        $errors = [];

        foreach ($destinations as $destination) {
            try {
                if ($destination->platform === 'fb_capi') {
                    app(\App\Domains\EventForwarding\Platforms\Facebook\FacebookService::class)->sendEvent($this->event, $destination);
                } elseif ($destination->platform === 'ga4') {
                    app(\App\Domains\EventForwarding\Platforms\GA4\GA4Service::class)->sendEvent($this->event, $destination);
                } elseif ($destination->platform === 'tiktok') {
                    app(\App\Domains\EventForwarding\Platforms\TikTok\TikTokService::class)->sendEvent($this->event, $destination);
                } elseif ($destination->platform === 'webhook') {
                    app(\App\Domains\EventForwarding\Platforms\Webhook\WebhookService::class)->sendEvent($this->event, $destination);
                }
            } catch (\Exception $e) {
                \Log::error("Event Forwarding Failed for {$destination->platform}: " . $e->getMessage());
                $failures[] = $destination->platform . ": " . $e->getMessage();
            }
        }

        // If there were any errors, we still want the job to be marked as failed for retries
        if (!empty($failures)) {
            Log::warning("Event Forwarding partially failed for some destinations: " . implode(', ', $failures));
        }
    }
}
