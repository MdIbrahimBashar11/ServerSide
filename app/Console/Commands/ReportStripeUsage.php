<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Domains\Projects\Models\Event;
use Illuminate\Support\Carbon;

class ReportStripeUsage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:report-usage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Counts the previous day\'s events for subscribed tenants and reports usage to Stripe API.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting SaaS Usage Report Sync...');
        
        $yesterdayStart = Carbon::yesterday()->startOfDay();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();

        // Limit only to tenants who have entered payment info
        $billableTenants = User::whereNotNull('stripe_id')->get();

        foreach ($billableTenants as $tenant) {
            // Check usage for the last 24h
            $eventCount = Event::whereIn('project_id', $tenant->projects()->pluck('id'))
                ->whereBetween('event_time', [$yesterdayStart, $yesterdayEnd])
                ->count();

            if ($eventCount > 0 && $tenant->subscribed('default')) {
                try {
                    // Metered billing logic via Cashier
                    $tenant->subscription('default')->reportUsageFor(env('STRIPE_METERED_PRICE_ID', 'price_default'), $eventCount);
                    $this->info("Successfully reported {$eventCount} events for Tenant ID: {$tenant->id}");
                } catch (\Exception $e) {
                    $this->error("Failed to report usage for Tenant ID: {$tenant->id}. Reason: " . $e->getMessage());
                    \Log::error('Stripe Usage Sync Failure', ['tenant_id' => $tenant->id, 'error' => $e->getMessage()]);
                }
            }
        }

        $this->info('Usage Sync Complete!');
    }
}
