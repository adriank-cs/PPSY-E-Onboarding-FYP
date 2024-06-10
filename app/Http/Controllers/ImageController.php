<?php

namespace App\Http\Controllers;

use App\Models\ModuleQuestion;
use App\Models\Profile;
use App\Models\User;
use App\Models\CompanyUser;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class ImageController extends Controller
{
    public function uploadImage(Request $request){

        $fileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->storeAs('TinyMceImages', $fileName, 'public');
        $path = 'storage/TinyMceImages/' . $fileName;

        // Manually construct the full URL
        $fullUrl = config('app.url') . '/' . $path;

        return response()->json(['location' => $fullUrl]);

        //return response()->json(['location' => asset('storage/' . $path)]);
    }
}