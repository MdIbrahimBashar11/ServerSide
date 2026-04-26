<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.packages.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Modify Subscription Plan</h1>
                <p class="text-sm text-gray-500 font-medium">Editing: {{ $package->name }}</p>
            </div>
        </div>

        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm space-y-8">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Col -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2">Core Settings</h3>
                    
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Plan Name</label>
                        <input name="name" type="text" value="{{ $package->name }}" class="block w-full bg-gray-50 border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition" required />
                        @error('name')<span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Monthly Cost ($)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">$</span>
                            <input name="price" type="number" step="0.01" value="{{ $package->price }}" class="block w-full pl-8 bg-gray-50 border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition" required />
                        </div>
                        @error('price')<span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Monthly Event Limit</label>
                        <input name="event_limit" type="number" value="{{ $package->event_limit }}" class="block w-full bg-gray-50 border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition" required />
                        @error('event_limit')<span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Right Col -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2">Stripe Sync Config</h3>
                    
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Stripe Product ID</label>
                        <input name="stripe_product_id" type="text" value="{{ $package->stripe_product_id }}" class="block w-full bg-gray-50 border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Stripe Price ID</label>
                        <input name="stripe_price_id" type="text" value="{{ $package->stripe_price_id }}" class="block w-full bg-gray-50 border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                </div>
            </div>

            <!-- Features Wrapper with Alpine -->
            <div x-data="{ features: {{ json_encode($package->features ?? ['']) }} }" class="pt-6 border-t border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Marketing Features Array</h3>
                
                <div class="space-y-3">
                    <template x-for="(feature, index) in features" :key="index">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <input x-model="features[index]" type="text" x-bind:name="`features[${index}]`" class="block w-full bg-white border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" required />
                            <button type="button" @click="features.splice(index, 1)" class="w-10 h-10 flex-shrink-0 bg-gray-100 hover:bg-rose-100 text-gray-500 hover:text-rose-600 rounded-lg flex items-center justify-center transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="mt-4">
                    <button type="button" @click="features.push('')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold text-sm transition">
                        + Add Feature
                    </button>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-xl font-extrabold text-sm shadow-xl shadow-emerald-500/30 transition transform hover:-translate-y-0.5 tracking-wide">
                    Save Changes
                </button>
            </div>

        </form>

    </div>
</x-app-layout>
