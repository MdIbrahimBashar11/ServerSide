<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-200 pb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Overview</h1>
                <p class="text-base text-gray-600 mt-2">Manage your tracking projects and account settings.</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('tickets.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-gray-300 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                    Support
                </a>
                <button onclick="checkAndCreateProject()" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 text-white text-sm font-bold shadow-md hover:bg-emerald-700 transition">
                    + Create Project
                </button>
            </div>
        </div>

        @if(session('status'))
            <div class="p-6 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-4 text-emerald-900 text-sm font-bold">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('status') }}
            </div>
        @endif

        <!-- Projects -->
        <section>
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-gray-900">Active Projects</h2>
                <span class="text-sm font-bold text-gray-500">{{ Auth::user()->projects()->count() }} Projects</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach(Auth::user()->projects as $project)
                <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm hover:border-emerald-300 transition-all">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $project->name }}</h3>
                            <p class="text-sm font-bold text-gray-500">{{ str_replace(['http://', 'https://'], '', $project->website_url) }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">Status: Active</span>
                    </div>

                    <div class="mb-10">
                        @php
                            $limit = Auth::user()->subscriptionPlan->event_limit ?? 10000;
                            $count = App\Domains\Projects\Models\Event::where('project_id', $project->id)->count();
                            $percent = min(($count / $limit) * 100, 100);
                        @endphp
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-bold text-gray-700">Monthly Usage</span>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($percent, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-3">
                            <div class="bg-emerald-600 h-full rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                        </div>
                        <div class="flex justify-between mt-3">
                            <p class="text-xs font-bold text-gray-600">{{ number_format($count) }} / {{ number_format($limit) }} events Used</p>
                            <p class="text-xs font-bold text-emerald-600">{{ number_format($limit - $count) }} Available</p>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm"></div>
                            <span class="text-xs font-bold text-gray-700">Data Flowing</span>
                        </div>
                        <div class="flex gap-4">
                            <a href="{{ route('projects.edit', $project->id) }}" class="text-sm font-bold text-gray-600 hover:text-gray-900 py-2 px-4">Edit</a>
                            <a href="{{ route('projects.show', $project->id) }}" class="px-6 py-2.5 rounded-lg bg-gray-900 text-white text-sm font-bold hover:bg-black transition">View Data</a>
                        </div>
                    </div>
                </div>
                @endforeach

                @if(Auth::user()->projects()->count() === 0)
                <div class="col-span-2 bg-white border-2 border-dashed border-gray-200 rounded-xl p-20 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mb-6 text-gray-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No projects found</h3>
                    <p class="text-base text-gray-600 mb-10 max-w-sm">Create your first tracking project to begin collecting data.</p>
                    <button onclick="checkAndCreateProject()" class="px-10 py-4 rounded-xl bg-emerald-600 text-white text-sm font-bold shadow-md hover:bg-emerald-700 transition">Create First Project</button>
                </div>
                @endif
            </div>
        </section>

        <!-- Subscription -->
        <section class="pt-12" id="plans">
            <div class="mb-10 text-center">
                <h2 class="text-2xl font-bold text-gray-900">Subscription Plans</h2>
                <p class="text-base text-gray-600 mt-2">Scale your data collection capacity.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($plans as $plan)
                    @php
                        $isCurrentPlan = ($hasPlan && Auth::user()->event_limit == $plan->event_limit);
                    @endphp
                    <div class="bg-white border rounded-xl p-8 flex flex-col justify-between shadow-sm {{ $isCurrentPlan ? 'border-emerald-600 ring-2 ring-emerald-600/10' : ($loop->iteration === 3 ? 'border-emerald-600/40 ring-1 ring-emerald-500/5' : 'border-gray-200') }}">
                        @if($isCurrentPlan)
                            <div class="text-center mb-4">
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase tracking-widest px-4 py-1 rounded-full border border-emerald-200">Current Plan</span>
                            </div>
                        @elseif($loop->iteration === 3)
                            <div class="text-center mb-4">
                                <span class="bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-widest px-4 py-1 rounded-full">Recommended</span>
                            </div>
                        @endif

                        <div class="text-center mb-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $plan->name }}</h3>
                            <p class="text-4xl font-bold text-gray-900 mb-1">৳{{ number_format($plan->price, 0) }}</p>
                            <p class="text-sm font-bold text-gray-500">per month</p>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-8 mb-10">
                            <ul class="space-y-4">
                                <li class="flex items-center gap-3 text-sm font-bold text-gray-700">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    {{ number_format($plan->event_limit) }} Events
                                </li>
                                <li class="flex items-center gap-3 text-sm font-bold text-gray-700">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    Priority Support
                                </li>
                            </ul>
                        </div>
                        
                        @if($isCurrentPlan)
                            <span class="w-full py-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-sm font-bold text-center block">
                                Your Current Plan
                            </span>
                        @else
                            <a href="{{ route('billing.checkout', $plan->id) }}" class="w-full py-4 rounded-xl text-sm font-bold text-center transition-all {{ $loop->iteration === 3 ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }}">
                                Choose Plan
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Modals -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const hasActivePlan = @json($hasPlan);
            function checkAndCreateProject() {
                if (!hasActivePlan) {
                    Swal.fire({
                        title: 'Plan Selection Required',
                        text: 'You must select a subscription plan before creating a project. Let\'s go pick your tier!',
                        icon: 'warning',
                        confirmButtonText: 'Select Plan Now',
                        confirmButtonColor: '#10b981'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('plans').scrollIntoView({ behavior: 'smooth' });
                        }
                    });
                    return;
                }
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-project' }));
            }
        </script>
        <x-modal name="create-project" focusable>
            <form method="post" action="{{ route('projects.store') }}" class="p-10 bg-white">
                @csrf
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900">New Project</h2>
                    <p class="text-base text-gray-600 mt-1">Specify your project details to begin tracking.</p>
                </div>

                <div class="space-y-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Project Name</label>
                        <input name="name" type="text" class="block w-full border-gray-300 rounded-xl py-4 px-6 text-gray-900 focus:ring-emerald-600 focus:border-emerald-600 transition font-bold" placeholder="My Business Site" required />
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Website URL</label>
                        <input name="custom_domain" type="text" class="block w-full border-gray-300 rounded-xl py-4 px-6 text-gray-900 focus:ring-emerald-600 focus:border-emerald-600 transition font-bold" placeholder="https://example.com" required />
                    </div>
                </div>

                <div class="mt-12 flex justify-end items-center gap-6">
                    <button type="button" x-on:click="$dispatch('close')" class="text-sm font-bold text-gray-500 hover:text-gray-900 transition">Cancel</button>
                    <button type="submit" class="px-10 py-4 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700 transition">Create Project</button>
                </div>
            </form>
        </x-modal>

    </div>
</x-app-layout>

