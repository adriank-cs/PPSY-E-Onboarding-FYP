<?php
//app\Models\OnboardingModule.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingModule extends Model
{
    protected $fillable = ['title', 'image', 'completion_percentage'];
}
