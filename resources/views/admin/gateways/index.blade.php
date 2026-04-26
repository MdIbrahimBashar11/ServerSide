<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-[#054a39] tracking-tight">Gateway Infrastructure</h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Configure global payment gateway credentials</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($gateways as $gateway)
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 {{ $gateway->is_active ? 'bg-emerald-500/10' : 'bg-gray-100' }} rounded-bl-3xl transition-colors"></div>
                    
                    <form action="{{ route('admin.gateways.update', $gateway) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="flex justify-between items-center mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl {{ $gateway->is_active ? 'bg-emerald-600' : 'bg-gray-200' }} flex items-center justify-center text-white font-black text-xs uppercase transition-colors">
                                    {{ substr($gateway->gateway_name, 0, 2) }}
                                </div>
                                <h3 class="text-lg font-black text-[#054a39] uppercase tracking-tight">{{ str_replace('_', ' ', $gateway->gateway_name) }}</h3>
                            </div>
                            
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" class="sr-only peer" {{ $gateway->is_active ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-1">Public ID / App Key</label>
                                <input type="password" name="client_id" value="{{ $gateway->client_id }}" class="w-full bg-gray-50 border-gray-100 rounded-xl text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500 transition">
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-1">Secret Key / API Token</label>
                                <input type="password" name="client_secret" value="{{ $gateway->client_secret }}" class="w-full bg-gray-50 border-gray-100 rounded-xl text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500 transition">
                            </div>

                            <button type="submit" class="w-full bg-[#054a39] hover:bg-[#075a46] text-white font-bold py-3 rounded-xl text-xs transition active:scale-95 shadow-lg shadow-emerald-900/10">SAVE CONFIGURATION</button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>

    </div>
</x-app-layout>
