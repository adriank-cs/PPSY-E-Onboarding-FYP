<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UserSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee1 = User::all()->find(2); //Lee Jong Suk LJS
        $employee2 = User::all()->find(4); //Lee Dong Wuk
        $admin = User::all()->find(1); //Leon
        $employeeOC = User::all()->find(3); //Bae Suzy


        //Company 1 employees
        $c1employees = [
            $employee1,
            $employee2,
        ];

        //5 sessions per week
        foreach ($c1employees as $employee) {

            //Simulate past 5 days
            for ($i = 1; $i <= 5; $i++) {
                
                $activityDay = now()->subDays($i);
                $morningShift = Carbon::parse($activityDay)->setTime(rand(8,12), rand(0,59), rand(0,59));
                $break = Carbon::parse($activityDay)->setTime(rand(13,14), rand(0,59), rand(0,59));

                //Morning Shift
                UserSession::create([
                    'id' => UserSession::generateUlid(), //Generate a new ULID
                    'UserID' => $employee->id,
                    'first_activity_at' => $morningShift,
                    'last_activity_at' => $break,
                    'duration' => $break->diff($morningShift)->format('%H:%I:%S'), //Default duration
                ]);
            }

            //Simulate next 5 days
            for ($i = 1; $i <= 5; $i++) {
    
                $activityDay = now()->addDays($i);
                $morningShift = Carbon::parse($activityDay)->setTime(rand(8,12), rand(0,59), rand(0,59));
                $break = Carbon::parse($activityDay)->setTime(rand(13,14), rand(0,59), rand(0,59));

                //Morning Shift
                UserSession::create([
                    'id' => UserSession::generateUlid(), //Generate a new ULID
                    'UserID' => $employee->id,
                    'first_activity_at' => $morningShift,
                    'last_activity_at' => $break,
                    'duration' => $break->diff($morningShift)->format('%H:%I:%S'), //Default duration
                ]);

            }
        }

        //Simulate 7 days
        for ($i = 1; $i <= 7; $i++) {
            $activityDay = now()->subDays($i);
            $morningShift = Carbon::parse($activityDay)->setTime(rand(8,12), rand(0,59), rand(0,59));
            $break = Carbon::parse($activityDay)->setTime(rand(13,14), rand(0,59), rand(0,59));

            //Morning Shift
            UserSession::create([
                'id' => UserSession::generateUlid(), //Generate a new ULID
                'UserID' => $admin->id,
                'first_activity_at' => $morningShift,
                'last_activity_at' => $break,
                'duration' => $break->diff($morningShift)->format('%H:%I:%S'), //Default duration
            ]);
        }

        //Simulate 2 days
        for ($i = 1; $i <= 7; $i++) {
            $activityDay = now()->subDays($i);
            $morningShift = Carbon::parse($activityDay)->setTime(rand(8,12), rand(0,59), rand(0,59));
            $break = Carbon::parse($activityDay)->setTime(rand(13,14), rand(0,59), rand(0,59));

            //Morning Shift
            UserSession::create([
                'id' => UserSession::generateUlid(), //Generate a new ULID
                'UserID' => $employeeOC->id,
                'first_activity_at' => $morningShift,
                'last_activity_at' => $break,
                'duration' => $break->diff($morningShift)->format('%H:%I:%S'), //Default duration
            ]);
        }


        
    }
}
