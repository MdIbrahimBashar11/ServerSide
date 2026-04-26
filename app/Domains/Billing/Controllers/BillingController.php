<?php

namespace App\Domains\Billing\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    /**
     * Show the checkout / gateway selection page.
     * Fixing the 404 by allowing GET and rendering a view.
     */
    public function checkout(SubscriptionPlan $plan, Request $request)
    {
        return view('billing.checkout', [
            'plan' => $plan,
            'user' => $request->user(),
        ]);
    }

    /**
     * Process the payment based on selected gateway.
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'gateway' => 'required|in:stripe,paypal,sslcommerz,bkash',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $gateway = $request->gateway;

        // Redirect based on gateway
        switch ($gateway) {
            case 'stripe':
                if (!$plan->stripe_price_id) {
                    return back()->with('error', 'Stripe identification missing for this plan.');
                }
                return $request->user()
                    ->newSubscription('default', $plan->stripe_price_id)
                    ->checkout([
                        'success_url' => route('dashboard') . '?payment=success',
                        'cancel_url' => route('billing.checkout', $plan),
                    ]);

            case 'paypal':
                // Logic for PayPal checkout initialization
                return back()->with('error', 'PayPal gateway is currently being initialized.');

            case 'sslcommerz':
                // Logic for SSLCommerz initiation
                return back()->with('error', 'SSLCommerz gateway is currently being initialized.');

            case 'bkash':
                // Logic for bKash initiation
                return back()->with('error', 'bKash gateway is currently being initialized.');
        }

        return back()->with('error', 'Invalid gateway selection.');
    }

    /**
     * Handle callbacks from various gateways.
     */
    public function callback(Request $request, $gateway)
    {
        Log::info("Payment Callback from {$gateway}", $request->all());

        // Generic logic to handle success/fail/cancel
        // This will be expanded as we implement each gateway service
        return redirect()->route('dashboard')->with('success', 'Payment status updated.');
    }

    public function downloadInvoice(Request $request, $invoiceId)
    {
        return $request->user()->downloadInvoice($invoiceId, [
            'vendor' => 'ServerTrack Protocol',
            'product' => 'Data Infrastructure Routing',
        ]);
    }
}
