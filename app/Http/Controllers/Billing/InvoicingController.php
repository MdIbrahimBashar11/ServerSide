<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillingInvoice;
use App\Services\PaymentService;

class InvoicingController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function selectGateway(BillingInvoice $invoice)
    {
        $this->authorize('view', $invoice);
        
        if ($invoice->status === 'paid') {
            return redirect()->route('dashboard');
        }

        $gateways = \App\Models\GatewaySetting::where('is_active', true)->get();
        
        return view('billing.select_gateway', compact('invoice', 'gateways'));
    }

    public function checkout(BillingInvoice $invoice, Request $request)
    {
        $this->authorize('view', $invoice);

        if ($invoice->status === 'paid') {
            return redirect()->route('dashboard')->with('status', 'already-paid');
        }

        $gateway = $request->input('gateway', 'stripe');
        
        try {
            return $this->paymentService->initiatePayment($invoice, $gateway);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function callback(Request $request, $gateway)
    {
        // Unified callback handler to mark invoices as paid
        // In a real scenario, this would verify signatures
        $invoiceId = $request->input('invoice');
        $invoice = BillingInvoice::findOrFail($invoiceId);

        $invoice->update([
            'status' => 'paid',
            'transaction_id' => $request->input('transaction_id', 'MOCKED-' . time()),
            'paid_at' => now(),
        ]);

        // Extend user subscription
        $user = $invoice->user;
        $user->update([
            'status' => 'active',
            'next_bill_date' => now()->addMonth(),
        ]);

        // If high balance affiliate referral, this is where we'd calculate commission
        if ($user->referred_by) {
            $referral = \App\Models\Referral::where('referred_id', $user->id)->first();
            if ($referral && $referral->status !== 'converted') {
                $commission = $invoice->amount * 0.10; // 10%
                $referral->update([
                    'status' => 'converted',
                    'commission_amount' => $commission
                ]);
                $referral->referrer->increment('affiliate_balance', $commission);
            }
        }

        return redirect()->route('dashboard')->with('status', 'payment-success');
    }
}
