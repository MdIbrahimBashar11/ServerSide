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

        /* Status badge */
        .status-badge {
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
        .status-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--em);
        }
    </style>

    <div class="flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-5xl bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm grid grid-cols-1 md:grid-cols-2">

            {{-- ── LEFT: Login Form ── --}}
            <div class="p-8 md:p-10 border-b md:border-b-0 md:border-r border-gray-100">

                {{-- Brand --}}
                <div class="flex items-center gap-2 mb-8">
                    <div class="w-2 h-2 rounded-full bg-green-600"></div>
                    <span class="text-xs font-extrabold text-gray-900 uppercase tracking-widest">TrackNode</span>
                </div>

                <h2 class="text-2xl font-extrabold text-gray-900 mb-1">Welcome back</h2>
                <p class="text-sm text-gray-500 font-medium mb-6">Reconnect to your infrastructure gateway.</p>

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

                <div class="or-divider">or sign in with email</div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               class="field-input" placeholder="your@endpoint.io" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>

                    {{-- Password --}}
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Access Key</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-green-600 hover:underline">Lost key?</a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password"
                               class="field-input" placeholder="••••••••" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="submit-btn">
                        Establish Connection
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </button>

                    <p class="text-center text-xs font-medium text-gray-400 mt-5">
                        New infrastructure?
                        <a href="{{ route('register') }}" class="text-green-600 font-semibold hover:underline underline-offset-2">Initialize Node</a>
                    </p>
                </form>
            </div>

            {{-- ── RIGHT: Branding / Info ── --}}
            <div class="p-8 md:p-10 bg-gray-50/60 flex flex-col justify-center">

                <div class="flex items-center gap-3 mb-6">
                    <span class="text-xs font-extrabold text-gray-900 uppercase tracking-widest">Gateway status</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <div class="status-badge mb-6">
                    <div class="status-badge-dot"></div>
                    All systems operational
                </div>

                <div class="space-y-4 mb-8">
                    <div class="p-4 rounded-xl bg-white border border-gray-100 shadow-sm">
                        <p class="text-xs font-extrabold text-gray-900 uppercase tracking-wider mb-2">Real-time Debugging</p>
                        <p class="text-[11px] text-gray-500 font-medium leading-relaxed">
                            Monitor every handshake and event packet in real-time with our advanced debugger console.
                        </p>
                    </div>
                    <div class="p-4 rounded-xl bg-white border border-gray-100 shadow-sm">
                        <p class="text-xs font-extrabold text-gray-900 uppercase tracking-wider mb-2">Global Edge Network</p>
                        <p class="text-[11px] text-gray-500 font-medium leading-relaxed">
                            Your nodes are deployed across 20+ global regions for ultra-low latency event processing.
                        </p>
                    </div>
                </div>

                {{-- Security Note --}}
                <div class="p-4 rounded-xl bg-gray-900">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 40px; height: 40px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <p class="text-[10px] font-black text-white uppercase tracking-widest">End-to-End Encrypted</p>
                    </div>
                    <p class="text-[10px] text-white leading-relaxed font-medium" >
                        All session keys are rotated every 24 hours. Your data is protected by military-grade AES-256 encryption.
                    </p>
                </div>

            </div>
        </div>
    </div>

</x-guest-layout>
