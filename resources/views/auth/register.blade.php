<x-guest-layout maxWidth="max-w-5xl">

    {{-- Plus Jakarta Sans from Google Fonts --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        :root {
            --em: #16a34a;
            --em-hover: #15803d;
            --em-light: #dcfce7;
            --em-ring: rgba(22, 163, 74, 0.15);
            --em-dark: #14532d;
        }

        /* Inputs */
        .field-input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #fff;
            font-size: 13.5px;
            font-weight: 500;
            color: #111827;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .field-input:focus {
            border-color: var(--em);
            box-shadow: 0 0 0 3px var(--em-ring);
        }
        .field-input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        /* Social buttons */
        .soc-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #fff;
            font-size: 12.5px;
            font-weight: 600;
            color: #111827;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            text-decoration: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .soc-btn:hover {
            border-color: var(--em);
            background: var(--em-light);
            color: var(--em-dark);
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            background: var(--em);
            border: none;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 13.5px;
            letter-spacing: 0.02em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .15s, transform .1s;
        }
        .submit-btn:hover { background: var(--em-hover); }
        .submit-btn:active { transform: scale(0.99); }

        /* Plan cards */
        .plan-card {
            padding: 14px;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            background: #fff;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            position: relative;
        }
        .plan-card:hover { border-color: var(--em); }
        .plan-card.active {
            border-color: var(--em);
            background: #f0fdf4;
        }

        .plan-check {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1.5px solid #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            transition: all .2s;
        }
        .plan-card.active .plan-check {
            border-color: var(--em);
            background: var(--em);
        }
        .plan-check-icon {
            width: 10px;
            height: 10px;
            stroke: #fff;
            opacity: 0;
            transition: opacity .2s;
        }
        .plan-card.active .plan-check-icon { opacity: 1; }

        .plan-name {
            font-size: 10.5px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 4px;
        }
        .plan-card.active .plan-name { color: var(--em-dark); }

        .plan-price {
            font-size: 20px;
            font-weight: 800;
            color: #111827;
            line-height: 1;
        }
        .plan-price-unit {
            font-size: 11px;
            font-weight: 500;
            color: #9ca3af;
        }

        .plan-ev {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #f3f4f6;
        }
        .plan-card.active .plan-ev { border-color: #bbf7d0; }

        .plan-feat-item {
            font-size: 11px;
            font-weight: 500;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 3px;
        }
        .plan-card.active .plan-feat-item { color: #166534; }
        .feat-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--em);
            flex-shrink: 0;
            opacity: 0.6;
        }

        /* Divider */
        .or-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #d1d5db;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 1.25rem 0;
        }
        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f3f4f6;
        }

        /* Trial badge */
        .trial-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border-radius: 20px;
            background: var(--em-light);
            font-size: 11.5px;
            font-weight: 600;
            color: var(--em-dark);
            margin-bottom: 1.25rem;
        }
        .trial-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--em);
        }
    </style>

    <div class="flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-5xl bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm grid grid-cols-1 md:grid-cols-2">

            {{-- ── LEFT: Form ── --}}
            <div class="p-8 md:p-10 border-b md:border-b-0 md:border-r border-gray-100">

                {{-- Brand --}}
                <div class="flex items-center gap-2 mb-8">
                    <div class="w-2 h-2 rounded-full bg-green-600"></div>
                    <span class="text-xs font-extrabold text-gray-900 uppercase tracking-widest">TrackNode</span>
                </div>

                <h2 class="text-2xl font-extrabold text-gray-900 mb-1">Create account</h2>
                <p class="text-sm text-gray-500 font-medium mb-6">Connect your infrastructure in minutes.</p>

                {{-- Social --}}
                <div class="grid grid-cols-2 gap-3 mb-2">
                    <a href="{{ route('social.redirect', 'google') }}" class="soc-btn">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </a>
                    <a href="{{ route('social.redirect', 'github') }}" class="soc-btn">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                        </svg>
                        GitHub
                    </a>
                </div>

                <div class="or-divider">or register with email</div>

                <form method="POST" action="{{ route('register') }}" x-data="{ selectedPlan: {{ $plans->first()->id ?? 0 }} }">
                    @csrf
                    <input type="hidden" name="plan_id" :value="selectedPlan">

                    {{-- Name --}}
                    <div class="mb-4">
                        <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Company name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                               class="field-input" placeholder="e.g. Acme Corporation" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               class="field-input" placeholder="ops@network.io" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>

                    {{-- Passwords --}}
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div>
                            <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password</label>
                            <input id="password" type="password" name="password"
                                   class="field-input" placeholder="••••••••" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Confirm</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   class="field-input" placeholder="••••••••" required />
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="submit-btn">
                        Create account
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </button>

                    <p class="text-center text-xs font-medium text-gray-400 mt-5">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:underline underline-offset-2">Sign in</a>
                    </p>
                </form>
            </div>

            {{-- ── RIGHT: Plans ── --}}
            <div class="p-8 md:p-10 bg-gray-50/60" x-data="{ selectedPlan: {{ $plans->first()->id ?? 0 }} }">

                <div class="flex items-center gap-3 mb-5">
                    <span class="text-xs font-extrabold text-gray-900 uppercase tracking-widest">Choose plan</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <div class="trial-badge mb-5">
                    <div class="trial-badge-dot"></div>
                    14-day free trial on all plans
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    @foreach($plans as $plan)
                        <div class="plan-card"
                             :class="selectedPlan === {{ $plan->id }} ? 'active' : ''"
                             @click="selectedPlan = {{ $plan->id }}">

                            <div class="plan-check">
                                <svg class="plan-check-icon" viewBox="0 0 24 24" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>

                            <div class="plan-name">{{ $plan->name }}</div>

                            <div class="plan-price">
                                ৳{{ number_format($plan->price) }}<span class="plan-price-unit">/mo</span>
                            </div>

                            <div class="plan-ev">{{ number_format($plan->event_limit / 1000) }}k events / mo</div>

                            @if($plan->features)
                                <div class="mt-1.5 space-y-0.5">
                                    @foreach(array_slice($plan->features, 0, 2) as $feature)
                                        <div class="plan-feat-item">
                                            <div class="feat-dot"></div>
                                            {{ $feature }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Enterprise --}}
                <div class="p-4 rounded-xl bg-white border border-gray-100">
                    <p class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1">Enterprise</p>
                    <p class="text-xs font-medium text-gray-500 leading-relaxed">
                        Need <span class="text-gray-800 font-semibold">5M+ events</span> or custom high-load clusters?
                        <a href="mailto:sales@tracknode.io" class="text-green-600 font-semibold hover:underline underline-offset-2">Contact us</a>
                        for a tailored quote.
                    </p>
                </div>

            </div>
        </div>
    </div>

</x-guest-layout>