<?php

namespace App\Domains\Billing\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends WebhookController
{
    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $priceId = $payload['data']['object']['plan']['id'] ?? null;
            $status = $payload['data']['object']['status'];

            if ($status === 'active' && $priceId) {
                $plan = SubscriptionPlan::where('stripe_price_id', $priceId)->first();
                
                if ($plan) {
                    $user->update([
                        'role' => 'premium',
                        'event_limit' => $plan->event_limit,
                    ]);
                    Log::info("Tenant " . $user->id . " dynamically upgraded via Webhook syncing to " . $plan->name);
                }
            } elseif (in_array($status, ['canceled', 'unpaid', 'past_due'])) {
                $user->update([
                    'role' => 'tenant',
                    'event_limit' => 10000, 
                ]);
                Log::info("Tenant " . $user->id . " downgraded due to Subscription state: " . $status);
            }
        }

        return parent::handleCustomerSubscriptionUpdated($payload);
    }
    
    protected function handleCustomerSubscriptionCreated(array $payload)
    {
        return $this->handleCustomerSubscriptionUpdated($payload);
    }
    
    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        return $this->handleCustomerSubscriptionUpdated($payload);
    }
}
