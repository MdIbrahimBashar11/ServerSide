<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        <!-- Navigation Tabs -->
        @include('projects._nav')

        <div class="max-w-5xl">
            @if(session('status'))
                <div class="mb-8 p-6 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-4 text-emerald-900 text-sm font-bold shadow-sm">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-6 bg-red-50 border border-red-200 rounded-xl flex items-center gap-4 text-red-900 text-sm font-bold shadow-sm">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <h1 class="text-3xl font-bold text-gray-900 tracking-tight mb-4">Implementation Guide</h1>
            <p class="text-base text-gray-600 mb-10">Follow these steps to integrate server-side tracking into your website with your own custom domain.</p>

            <div class="space-y-12">
                <!-- Step 1: Custom Domain Tracking (The CNAME setup) -->
                <section class="bg-[#f0f9f6] border border-[#d1e9e0] rounded-2xl p-10 shadow-sm">
                    <div class="flex items-center gap-6 mb-8">
                        <div class="w-12 h-12 bg-gray-900 text-white rounded-xl flex items-center justify-center font-bold text-lg shadow-lg">1</div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Custom Domain Setup</h2>
                            <p class="text-gray-600 text-sm mt-1">Connect your own domain for professional, first-party tracking.</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">CNAME Target (Point your DNS to this)</label>
                            <div class="flex items-center gap-4 bg-white p-5 rounded-xl border border-gray-200 shadow-sm group">
                                <code class="flex-1 font-mono text-sm text-gray-900 tracking-wider">sdk.recordsync.cam</code>
                                <button onclick="navigator.clipboard.writeText('sdk.recordsync.cam')" class="text-gray-400 hover:text-emerald-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </button>
                            </div>
                            <p class="text-[10px] text-gray-500 font-bold mt-3 uppercase tracking-tight italic opacity-60">Copy this value. You will need it for your DNS settings.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Your Tracking Subdomain</label>
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                                <span class="font-bold text-gray-900">{{ $project->custom_domain }}</span>
                                <div class="flex items-center gap-3">
                                    @if($project->domain_status === 'verified')
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-widest">
                                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse mr-2"></span> DNS Verified
                                        </span>
                                        
                                        @if($project->ssl_status === 'active')
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold bg-gray-900 text-white border border-gray-800 uppercase tracking-widest">
                                                <svg class="w-3 h-3 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                SSL Active
                                            </span>
                                        @elseif($project->ssl_status === 'pending')
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-widest">
                                                <span class="w-2 h-2 bg-blue-500 rounded-full animate-bounce mr-2"></span> Provisioning SSL...
                                            </span>
                                        @elseif($project->ssl_status === 'failed')
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-100 uppercase tracking-widest">SSL Failed</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-widest">Pending DNS Check</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-500 font-bold mt-4 leading-relaxed">Enter the subdomain you want to use (e.g., track.yourstore.com).<br>Do not use common names like 'pixel' or 'analytics' to avoid browser blocking.</p>
                        </div>

                        <!-- Connection Guide Box -->
                        <div class="bg-white border border-gray-200 rounded-xl p-8 space-y-6">
                            <h3 class="text-sm font-bold text-gray-900 border-l-4 border-emerald-600 pl-4 uppercase tracking-widest text-[10px]">How to connect:</h3>
                            <ul class="space-y-4 text-xs font-bold text-gray-600 uppercase tracking-tighter">
                                <li class="flex items-start gap-3"><span class="text-emerald-600">01.</span> Log in to your domain registrar (GoDaddy, Namecheap, etc.)</li>
                                <li class="flex items-start gap-3"><span class="text-emerald-600">02.</span> Navigate to DNS Management or Zone Settings.</li>
                                <li class="flex items-start gap-3"><span class="text-emerald-600">03.</span> Add a new record: <span class="text-gray-900 font-bold">CNAME</span></li>
                                <li class="flex items-start gap-3"><span class="text-emerald-600">04.</span> Host: Your subdomain prefix (e.g., 'track')</li>
                                <li class="flex items-start gap-3"><span class="text-emerald-600">05.</span> Value/Target: <span class="text-gray-900 font-bold">sdk.recordsync.cam</span></li>
                                <li class="flex items-start gap-3"><span class="text-emerald-600">06.</span> Save, wait for propagation, then click verify below.</li>
                            </ul>
                        </div>

                        <form action="{{ route('projects.verify_domain', $project->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-5 bg-gray-900 text-white rounded-xl font-bold text-sm shadow-xl flex items-center justify-center gap-3 transition hover:bg-black active:scale-[0.98] uppercase tracking-widest">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Connect Domain & Auto-SSL
                            </button>
                        </form>
                    </div>
                </section>

                <!-- Step 2: Implementation -->
                <section x-data="{ tab: 'laravel' }" class="bg-white border border-gray-200 rounded-xl p-10 shadow-sm">
                    <div class="flex items-center gap-6 mb-10">
                        <div class="w-12 h-12 bg-gray-900 text-white rounded-xl flex items-center justify-center font-bold text-lg shadow-lg">2</div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Platform Integration</h2>
                            <p class="text-gray-600 text-sm mt-1">Send tracking events from your application using our server-side SDK benchmarks.</p>
                        </div>
                    </div>

                    <!-- Tab Headers -->
                    <div class="flex flex-wrap gap-2 mb-10 bg-gray-100 p-1.5 rounded-xl">
                        <button @click="tab = 'laravel'" :class="tab === 'laravel' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-900'" class="px-8 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest transition">Laravel</button>
                        <button @click="tab = 'wordpress'" :class="tab === 'wordpress' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-900'" class="px-8 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest transition">WordPress</button>
                        <button @click="tab = 'node'" :class="tab === 'node' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-900'" class="px-8 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest transition">Node.js</button>
                        <button @click="tab = 'nextjs'" :class="tab === 'nextjs' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-900'" class="px-8 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest transition">Next.js</button>
                        <button @click="tab = 'curl'" :class="tab === 'curl' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-900'" class="px-8 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest transition">Raw cURL</button>
                    </div>

                    <!-- Tab Contents -->
                    <div class="space-y-10">
                        @php
                            $trackingUrl = $project->domain_status === 'verified' 
                                ? "https://{$project->custom_domain}/api/events" 
                                : config('app.url') . "/api/events";
                        @endphp

                        <!-- Laravel -->
                        <div x-show="tab === 'laravel'" x-transition class="space-y-6">
                            <div class="bg-gray-950 rounded-2xl p-10 overflow-hidden shadow-2xl relative group">
                                <button onclick="navigator.clipboard.writeText(this.nextElementSibling.innerText)" class="absolute top-6 right-6 text-gray-500 hover:text-white transition uppercase text-[10px] font-bold">Copy Snippet</button>
                                <pre class="text-emerald-400 font-mono text-xs leading-relaxed">
use Illuminate\Support\Facades\Http;

Http::post('{{ $trackingUrl }}', [
    'event_name' => 'Purchase',
    'event_id'   => 'unique_id_' . time(),
    'timestamp'  => time(),
    'user_data' => [
        'email' => 'customer@example.com',
        'phone' => '1234567890',
        'client_ip_address' => request()->ip(),
        'client_user_agent' => request()->userAgent(),
    ],
    'custom_data' => [
        'value'     => 149.00,
        'currency'  => 'USD',
    ]
]);</pre>
                            </div>
                        </div>

                        <!-- Node.js -->
                        <div x-show="tab === 'node'" x-transition style="display: none;" class="space-y-6">
                            <div class="bg-gray-950 rounded-2xl p-10 overflow-hidden shadow-2xl relative group">
                                <button onclick="navigator.clipboard.writeText(this.nextElementSibling.innerText)" class="absolute top-6 right-6 text-gray-500 hover:text-white transition uppercase text-[10px] font-bold">Copy Snippet</button>
                                <pre class="text-emerald-400 font-mono text-xs leading-relaxed">
const axios = require('axios');

axios.post('{{ $trackingUrl }}', {
  event_name: 'Lead',
  event_id: 'node_' + Date.now(),
  user_data: {
    email: 'user@domain.com',
    client_ip_address: '0.0.0.0'
  }
});</pre>
                            </div>
                        </div>

                        <!-- cURL -->
                        <div x-show="tab === 'curl'" x-transition style="display: none;" class="space-y-6">
                            <div class="bg-gray-950 rounded-2xl p-10 overflow-hidden shadow-2xl relative group">
                                <button onclick="navigator.clipboard.writeText(this.nextElementSibling.innerText)" class="absolute top-6 right-6 text-gray-500 hover:text-white transition uppercase text-[10px] font-bold">Copy Snippet</button>
                                <pre class="text-emerald-400 font-mono text-xs leading-relaxed">
curl -X POST "{{ $trackingUrl }}" \
-H "Content-Type: application/json" \
-d '{
    "event_name": "PageView",
    "event_id": "curl_ident_123",
    "user_data": {
        "email": "test@test.com"
    }
}'</pre>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="mt-12 p-10 bg-emerald-900 border border-emerald-800 rounded-2xl max-w-5xl shadow-2xl shadow-emerald-900/20">
                <h3 class="text-lg font-bold text-emerald-400 tracking-tight mb-4 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    SaaS Production Guidance
                </h3>
                <p class="text-sm text-emerald-100 leading-relaxed font-bold uppercase tracking-wider opacity-80">
                    Your infrastructure is currently configured to handle up to 10,000 concurrent node transmissions. If you require dedicated hardware scaling or custom Nginx orchestration for wildcard CNAME pools, please contact our senior architectural team via <a href="{{ route('tickets.index') }}" class="text-white border-b border-emerald-400 pb-0.5 hover:text-emerald-300">Engineering Support</a>.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
