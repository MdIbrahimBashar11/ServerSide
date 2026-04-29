<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Domains\Dashboard\Controllers\DashboardController;
use App\Domains\Dashboard\Controllers\IntegrationController;

use App\Domains\Docs\Controllers\DocsController;

Route::get('/', function () {
    return view('welcome', [
        'packages' => \App\Models\SubscriptionPlan::orderBy('price', 'asc')->get()
    ]);
})->name('home');

// Social Authentication Routes
Route::get('auth/{provider}/redirect', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirect'])->name('social.redirect');
Route::get('auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'callback'])->name('social.callback');

Route::get('/docs/{page?}', [DocsController::class, 'show'])->name('docs');

use App\Domains\Admin\Controllers\AdminDashboardController;

use App\Domains\Projects\Controllers\ProjectController;
use App\Http\Controllers\Support\TicketController;

Route::get('/billing/checkout/{plan}', [\App\Domains\Billing\Controllers\BillingController::class, 'checkout'])->name('billing.checkout');

Route::middleware(['auth', 'verified'])->group(function () {
    // Tenant Home
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Project Specific Portal
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/export', [ProjectController::class, 'export'])->name('projects.export');
    Route::get('/projects/{project}/config', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('/projects/{project}/config', [ProjectController::class, 'update'])->name('projects.update');
    Route::get('/projects/{project}/setup', [ProjectController::class, 'setup'])->name('projects.setup');
    Route::post('/projects/{project}/verify-domain', [ProjectController::class, 'verifyDomain'])->name('projects.verify_domain');
    Route::post('/projects/{project}/destinations', [ProjectController::class, 'updateDestinations'])->name('projects.destinations.update');
    Route::get('/projects/{project}/events/{event}/logs', [ProjectController::class, 'deliveryLogs'])->name('projects.event.logs');

    // Primary Payment Gateway
    Route::get('/billing/pay/{plan}', [\App\Domains\Billing\Controllers\BillingController::class, 'checkout'])->name('billing.pay');
    Route::post('/billing/process', [\App\Domains\Billing\Controllers\BillingController::class, 'processPayment'])->name('billing.process');
    Route::get('/billing/callback/{gateway}', [\App\Domains\Billing\Controllers\BillingController::class, 'callback'])->name('billing.callback');
    Route::post('/billing/callback/{gateway}', [\App\Domains\Billing\Controllers\BillingController::class, 'callback'])->name('billing.callback.post');
    Route::get('/billing/download/{invoice}', [\App\Domains\Billing\Controllers\BillingController::class, 'downloadInvoice'])->name('billing.download');

    // Legacy / Admin Invoicing
    Route::get('/billing', function() {
        return view('billing.index', ['user' => auth()->user()]);
    })->name('billing.index');
    Route::get('/billing/select-gateway/{invoice}', [\App\Http\Controllers\Billing\InvoicingController::class, 'selectGateway'])->name('billing.select_gateway');
    Route::post('/billing/checkout-invoice/{invoice}', [\App\Http\Controllers\Billing\InvoicingController::class, 'checkout'])->name('billing.invoice.checkout');

    // Super Admin: Gateway Settings
    Route::prefix('admin')->name('admin.')->group(function() {
        Route::get('/gateways', [\App\Http\Controllers\Admin\GatewaySettingsController::class, 'index'])->name('gateways.index');
        Route::patch('/gateways/{gateway}', [\App\Http\Controllers\Admin\GatewaySettingsController::class, 'update'])->name('gateways.update');
    });

    // Affiliate System
    Route::get('/affiliate', [\App\Http\Controllers\AffiliateController::class, 'index'])->name('affiliate.index');

    // Account Settings (Consolidated)
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::patch('/settings/phone', [\App\Http\Controllers\SettingsController::class, 'updatePhone'])->name('settings.phone');

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/message', [TicketController::class, 'reply'])->name('tickets.reply');
});

// Stripe Cashier Webhook Override
Route::post('/stripe/webhook', '\App\Domains\Billing\Controllers\StripeWebhookController@handleWebhook');

use App\Http\Controllers\Admin\AdminTicketController;

Route::middleware(['auth', 'verified', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::patch('/users/{user}/toggle', [AdminDashboardController::class, 'toggleStatus'])->name('users.toggle');
    
    // Packages
    Route::resource('packages', \App\Domains\Admin\Controllers\AdminPackageController::class);
    
    // Admin Tickets
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('tickets.reply');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
