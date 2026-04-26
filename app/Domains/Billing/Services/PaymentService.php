<?php

namespace App\Domains\Billing\Services;

use App\Models\User;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Finalize a manual subscription update (for SSLCommerz/bKash/PayPal).
     * Since these don't use Stripe's automatic objects, we handle them here.
     */
    public function finalizePayment(User $user, SubscriptionPlan $plan, string $gateway, string $transactionId)
    {
        Log::info("Finalizing Payment for User ID: {$user->id} via {$gateway}. Transaction: {$transactionId}");

        // Update User Model with new plan details
        $user->update([
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'next_bill_date' => Carbon::now()->addMonth(), // Standard 30-day renewal
        ]);

        // Create a dedicated Invoice record for tracking
        $user->invoices()->create([
            'amount' => $plan->price,
            'plan_id' => $plan->id,
            'gateway' => $gateway,
            'transaction_id' => $transactionId,
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        return true;
    }
}
