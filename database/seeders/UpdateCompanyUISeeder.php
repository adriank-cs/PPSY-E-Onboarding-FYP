<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            $company->update([
                'company_logo' => $this->generateLogoPath($company),
                'sidebar_color' => $this->generateRandomColor(),
                'button_color' => $this->generateRandomColor()
            ]);
        }
    }

    /**
     * Generate a random color.
     *
     * @return string
     */
    private function generateRandomColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    /**
     * Generate a logo path.
     *
     * @param Company $company
     * @return string
     */
    private function generateLogoPath(Company $company)
    {
        // For demonstration, using a fixed image. You can implement your own logic.
        $logoName = Str::slug($company->Name) . '-logo.png';
        $logoPath = 'CompanyLogos/' . $logoName;

        // Ensure the directory exists
        if (!Storage::disk('public')->exists('CompanyLogos')) {
            Storage::disk('public')->makeDirectory('CompanyLogos');
        }

        // Copy a default logo to this path (assuming you have a default logo stored somewhere)
        Storage::disk('public')->copy('default/logo.png', $logoPath);

        return $logoPath;
    }
}
