<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OnboardingModule;

class OnboardingModuleSeeder extends Seeder
{


    public function run()
    {
        OnboardingModule::create([
            'title' => 'Admin Department Onboarding',
            'image' => 'admin-department.png',
            'completion_percentage' => 30,
        ]);

        OnboardingModule::create([
            'title' => 'Company History Onboarding',
            'image' => 'company-history.png',
            'completion_percentage' => 40,
        ]);

        // Add more sample modules as needed
    }
}
