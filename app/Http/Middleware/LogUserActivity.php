<?php

namespace App\Http\Middleware;

use App\Models\UserSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            //To track session
            $session = 0; 

            //If company user is null
            if ($user == null) {
                //Exit function
                return $next($request);
            }

            //If user has no active session
            if ($user->last_active_session == null) {
                Log::info("Session created");
                //Create new user session
                $session = UserSession::create([
                    'id' => UserSession::generateUlid(), //Generate a new ULID
                    'UserID' => $user->id,
                    'first_activity_at' => now(),
                    'last_activity_at' => now(),
                    'duration' => now()->diff(now())->format('%H:%I:%S'), //Default duration
                ]);

                //Update the user's last active session
                $user->last_active_session = $session->id;
                $user->save();

                //Log::info(print_r($session, true));
            }
            else {
                //Find user's last active session
                $session = UserSession::find($user->last_active_session);
            }

            //Check user's inactivity
            if (Carbon::parse($session->last_activity_at)->addHour()->lessThan(now())) {
                //If user is inactive for more than 1 hour
                //Create new user session
                $session = UserSession::create([
                    'id' => UserSession::generateUlid(), //Generate a new ULID
                    'UserID' => $user->id,
                    'first_activity_at' => now(),
                    'last_activity_at' => now(),
                    'duration' => now()->diff(now())->format('%H:%I:%S'), //Default duration
                ]);

                //Update the user's last active session
                $user->last_active_session = $session->id;
                $user->save();

            }
            else {
                //Update the user's last active session
                $session->last_activity_at = now();
                $session->duration = $session->last_activity_at->diff($session->first_activity_at)->format('%H:%I:%S');
                $session->save();
            }
            
        }

        return $next($request);
    }

}
