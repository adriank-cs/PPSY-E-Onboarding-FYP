<?php 

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Profile;
use App\Models\User;
use App\Mail\CustomResetPassword;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller {

    //Dashboard
    function dashboard()
    {
        return view('employee.dashboard');
    }
    function profile_page() {
        // Get the authenticated user
        $user = auth()->user();

        // Fetch the profile data for the logged-in user
        $employee = Profile::where('user_id', $user->id)->first();

        // Check if the user has a company user record
        if ($user->companyUser) {
            // Get the company ID from the user's company user record
            $companyId = $user->companyUser->CompanyID;

            $company = Company::find($companyId);
           
            $buttonColor = $company->button_color;

            // Fetch profiles belonging to the specified company ID
            $profiles = Profile::join('companyusers', 'profiles.user_id', '=', 'companyusers.UserID')
                ->where('companyusers.CompanyID', '=', $companyId)
                ->get();

            // Pass the profiles to the view
            return view('employee.profile-page', compact('user', 'employee', 'profiles','buttonColor'));  
        }

        // Handle the case when the user doesn't have a company user record
        return redirect()->route('login')->with('error', 'User does not have a company association.');
        return view('employee.profile-page'); //BUG: This line is unreachable
    }

    public function generateDynamicCss($companyId){
        $company = Company::find($companyId);
        $css = View::make('css.colors', compact('company'))->render();
        file_put_contents(public_path('css/colors.css'), $css);
    }
}
    

?>
