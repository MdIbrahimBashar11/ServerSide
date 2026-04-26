<?php

namespace App\Domains\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;

class AdminPackageController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('price', 'asc')->get();
        return view('admin.packages.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'event_limit' => 'required|integer|min:0',
            'stripe_product_id' => 'nullable|string|max:255',
            'stripe_price_id' => 'nullable|string|max:255',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255'
        ]);

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.packages.index')->with('status', 'Subscription Plan Created Successfully!');
    }

    public function edit(SubscriptionPlan $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, SubscriptionPlan $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'event_limit' => 'required|integer|min:0',
            'stripe_product_id' => 'nullable|string|max:255',
            'stripe_price_id' => 'nullable|string|max:255',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255'
        ]);

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('status', 'Subscription Plan Updated Successfully!');
    }

    public function destroy(SubscriptionPlan $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('status', 'Subscription Plan Deleted!');
    }
}
