<?php
//app\Models\OnboardingModule.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnboardingModule extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'image', 'completion_percentage'];
}
