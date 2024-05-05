<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = User::all()->find(2); //Lee Jong Suk LJS
        $admin = User::all()->find(1); //Leon

        //Flow Completion
        activity()
        ->causedBy($employee)
        ->withProperties([
            'moduleid' => '1',
            'module' => 'Admin Department Onboarding'
            ])
        ->event('Flow Completion')
        ->log('Flow Completion');
        
        //Flow Completion
        activity()
        ->causedBy($employee)
        ->withProperties([
            'moduleid' => '2',
            'module' => 'Company History Onboarding'
            ])
        ->event('Flow Completion')
        ->log('Flow Completion');

        //Quiz Completion
        activity()
        ->causedBy($admin)
        ->withProperties([
            'chapter' => '2',
            'quiz' => '6',
            'module' => 'Company History Onboarding'
            ])
        ->event('Quiz Completion')
        ->log('Quiz Completion');

        //Quiz Completion
        activity()
        ->causedBy($employee)
        ->withProperties([
            'chapter' => '2',
            'quiz' => '6',
            'module' => 'Company History Onboarding'
            ])
        ->event('Quiz Completion')
        ->log('Quiz Completion');
        
    }
}
