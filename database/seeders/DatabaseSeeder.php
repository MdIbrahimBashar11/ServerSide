<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Models\Event;
use App\Domains\Projects\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Seed Subscription Plans
        \App\Models\SubscriptionPlan::create([
            'name' => 'Free Trial',
            'price' => 0.00,
            'event_limit' => 1000,
            'features' => ['1,000 Edge Events / mo', '1 Custom Domain', 'Basic Community Support']
        ]);

        \App\Models\SubscriptionPlan::create([
            'name' => 'SOHO',
            'price' => 29.00,
            'event_limit' => 50000,
            'features' => ['50,000 Edge Events / mo', '3 Custom Domains', 'Email Priority Support']
        ]);

        \App\Models\SubscriptionPlan::create([
            'name' => 'Scale',
            'price' => 99.00,
            'event_limit' => 250000,
            'features' => ['250,000 Edge Events / mo', 'Unlimited Domains', '1-on-1 Slack Connect']
        ]);

        \App\Models\SubscriptionPlan::create([
            'name' => 'Enterprise',
            'price' => 299.00,
            'event_limit' => 1000000,
            'features' => ['1,000,000 Edge Events / mo', 'Dedicated Account Manager', 'Custom Data Ingestion API']
        ]);

        // 1. Compile Super Admin User
        $admin = User::create([
            'name' => 'Systems Architect',
            'email' => 'superadmin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // 2. Compile Demo Tenant User
        $tenant = User::create([
            'name' => 'Demo Tenant',
            'email' => 'tenant@demo.com',
            'password' => Hash::make('password'),
            'role' => 'tenant',
            'status' => 'active',
            'email_verified_at' => now(),
            'event_limit' => 100000,
            'trial_ends_at' => now()->addDays(14),
        ]);

        // 3. Mount Tracking Server Deck (Project)
        $project = Project::factory()->create([
            'user_id' => $tenant->id,
            'name' => 'RecordSync Master App',
            'custom_domain' => 'trk.recordsync.cam',
            'website_url' => 'https://recordsync.cam',
            'platform' => 'laravel',
        ]);

        // 4. Attach Meta CAPI & GA4 Destinations
        Destination::create([
            'project_id' => $project->id,
            'platform' => 'fb_capi',
            'dataset_id' => '1029384756', // Fake Pixel ID
            'access_token' => 'EAAI' . Str::random(40),
            'is_active' => true,
        ]);

        Destination::create([
            'project_id' => $project->id,
            'platform' => 'ga4',
            'dataset_id' => 'G-ABCDEF1234', // Fake GA4 ID
            'access_token' => 'secret_' . Str::random(20),
            'is_active' => true,
        ]);

        Destination::create([
            'project_id' => $project->id,
            'platform' => 'webhook',
            'dataset_id' => 'https://crm.hubspot.com/api/catch-webhook', // Target URL
            'access_token' => Str::random(32),
            'is_active' => true,
        ]);

        // 5. Inundate Database with 1,500 Synthetic Traffic Events across 7 days
        $this->command->info('Synthesizing 1,500 tracking payloads... please hold.');
        Event::factory()->count(1500)->create([
            'project_id' => $project->id,
        ]);

        // 6. Generate Support Ticket Infrastructure
        $ticket = Ticket::create([
            'user_id' => $tenant->id,
            'subject' => 'Meta Conversion API Rate Limiting Issue',
            'status' => 'answered'
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $tenant->id,
            'message' => "Hello Team,\n\nMy tracking dashboard shows occasional 429 Status Codes from Facebook. Is my server edge rate-limiting the requests?",
            'created_at' => now()->subDays(2)
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $admin->id,
            'message' => "Hello Demo Tenant,\n\nWe have initiated your diagnostic scan. Our exponential backoff system automatically protects your Meta requests! It will retry at intervals of 10s, 30s, 60s, and 300s. No traffic will be lost.\n\nRegards,\nSystems Architect",
            'created_at' => now()->subDays(1)
        ]);

        $this->command->info('Database Demonstration Environment fully weaponized.');
    }
}
