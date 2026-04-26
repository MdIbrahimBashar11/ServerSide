<?php

namespace App\Services;

use App\Models\GatewaySetting;
use App\Models\BillingInvoice;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    /**
     * Unified interface to initiate payment
     */
    public function initiatePayment(BillingInvoice $invoice, string $gateway)
    {
        $setting = GatewaySetting::where('gateway_name', $gateway)->where('is_active', true)->first();

        if (!$setting && $gateway !== 'stripe') {
            throw new \Exception("Payment gateway {$gateway} is not configured or active.");
        }

        return match ($gateway) {
            'stripe' => $this->initiateStripe($invoice),
            'bkash' => $this->initiateBkash($invoice, $setting),
            'sslcommerz' => $this->initiateSslCommerz($invoice, $setting),
            default => throw new \Exception("Gateway {$gateway} not supported."),
        };
    }

    protected function initiateStripe(BillingInvoice $invoice)
    {
        // Stripe implementation using Laravel Cashier
        $user = $invoice->user;
        return $user->checkoutCharge($invoice->amount * 100, "Invoice #{$invoice->id}", 1, [
            'success_url' => route('dashboard') . '?success=1',
            'cancel_url' => route('dashboard') . '?cancel=1',
        ]);
    }

    protected function initiateBkash(BillingInvoice $invoice, GatewaySetting $setting)
    {
        // Mocking bKash API response
        $invoice->update(['gateway' => 'bkash', 'status' => 'pending']);
        return redirect()->away("https://sandbox.bkash.com/payment?invoice={$invoice->id}&amount={$invoice->amount}");
    }

    protected function initiateSslCommerz(BillingInvoice $invoice, GatewaySetting $setting)
    {
        // Mocking SSLCommerz API response
        $invoice->update(['gateway' => 'sslcommerz', 'status' => 'pending']);
        return redirect()->away("https://sandbox.sslcommerz.com/gwprocess?invoice={$invoice->id}&amount={$invoice->amount}");
    }
}
