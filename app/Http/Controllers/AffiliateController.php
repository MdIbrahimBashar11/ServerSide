<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ensure user has an affiliate code
        if (!$user->affiliate_code) {
            $user->update([
                'affiliate_code' => 'REF-' . strtoupper(substr(md5($user->id . time()), 0, 8))
            ]);
        }

        $referrals = $user->referrals()->with('referred')->latest()->get();
        
        $stats = [
            'total_referrals' => $referrals->count(),
            'paid_servers' => $referrals->where('status', 'converted')->count(),
            'free_servers' => $referrals->where('status', 'pending')->count(),
            'total_earned' => $user->affiliate_balance,
            'this_month_earned' => $user->referrals()->whereMonth('created_at', now()->month)->sum('commission_amount'),
        ];

        return view('affiliate.dashboard', compact('user', 'referrals', 'stats'));
    }
}
