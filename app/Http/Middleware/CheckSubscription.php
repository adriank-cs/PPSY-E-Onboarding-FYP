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

        // Check if the user is authenticated and has a companyUser relationship
        if ($user && $user->companyUser) {
            $companyUser = $user->companyUser;
            $company = \App\Models\Company::find($companyUser->CompanyID);

            if ($company && $company->subscription_ends_at) {
                $expiryDate = Carbon::parse($company->subscription_ends_at);
                $gracePeriod = $expiryDate->copy()->addDays(3);
                $currentDate = Carbon::now();

                if ($currentDate->greaterThan($gracePeriod)) {
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Your company subscription has expired. Please contact your administrator.');
                }
            }
        }

        return $next($request);
    }
}
