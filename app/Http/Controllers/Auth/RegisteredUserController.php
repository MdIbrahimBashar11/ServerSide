<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $plans = \App\Models\SubscriptionPlan::all();
        return view('auth.register', compact('plans'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'plan_id' => ['required', 'exists:subscription_plans,id'],
        ]);

        $referrer_id = null;
        if ($request->cookie('affiliate_ref')) {
            $referrer = User::where('affiliate_code', $request->cookie('affiliate_ref'))->first();
            if ($referrer) {
                $referrer_id = $referrer->id;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referred_by' => $referrer_id,
            'status' => 'active',
            'next_bill_date' => now()->addDays(14), // 14 Day Trial
        ]);

        // Create Referral Record
        if ($referrer_id) {
            \App\Models\Referral::create([
                'referrer_id' => $referrer_id,
                'referred_id' => $user->id,
                'status' => 'pending',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        $plan = \App\Models\SubscriptionPlan::find($request->plan_id);
        if ($plan && $plan->price > 0) {
            $invoice = \App\Models\BillingInvoice::create([
                'user_id' => $user->id,
                'status' => 'unpaid',
                'amount' => $plan->price,
                'due_date' => now(), // First bill
            ]);
            return redirect()->route('billing.select_gateway', $invoice);
        }

        return redirect(route('dashboard', absolute: false));
    }
}
