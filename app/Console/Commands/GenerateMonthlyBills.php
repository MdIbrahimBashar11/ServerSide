<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateMonthlyBills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-monthly-bills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly billing invoices and deactivate overdue accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting billing generation...');

        // 1. Generate Invoices for due users
        $dueUsers = \App\Models\User::where('role', 'tenant')
            ->where('status', 'active')
            ->where('next_bill_date', '<=', now())
            ->get();

        foreach ($dueUsers as $user) {
            $plan = $user->subscriptions()->first()?->plan ?? \App\Models\SubscriptionPlan::first(); // Fallback if no cashier sub
            
            \App\Models\BillingInvoice::create([
                'user_id' => $user->id,
                'amount' => $plan->price,
                'status' => 'pending',
                'due_date' => now()->addDays(3),
            ]);

            // Prevent double generation for same month
            $user->update(['next_bill_date' => now()->addMonth()]);
            
            $this->info("Generated invoice for user: {$user->email}");
        }

        // 2. Deactivate Overdue Users
        $overdueInvoices = \App\Models\BillingInvoice::where('status', 'pending')
            ->where('due_date', '<', now())
            ->get();

        foreach ($overdueInvoices as $invoice) {
            $invoice->update(['status' => 'overdue']);
            $invoice->user->update(['status' => 'inactive']);
            $this->warn("Deactivated user: {$invoice->user->email} due to overdue invoice #{$invoice->id}");
        }

        $this->info('Billing process completed.');
    }
}
