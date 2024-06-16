<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $profile = $user->profile;

        if ($profile && $profile->subscription_ends_at) {
            $expiryDate = Carbon::parse($profile->subscription_ends_at);
            $gracePeriod = $expiryDate->copy()->addDays(3);
            $currentDate = Carbon::now();

            if ($currentDate->greaterThan($gracePeriod)) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your subscription has expired. Please renew your subscription to continue.');
            }
        }

        return $next($request);
    }
}
