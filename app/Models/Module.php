<?php

/* Module.php */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; //TODO: Check if this directive is required
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'image_path', 'CompanyID'];

    // Define the relationship with the Company model
    public function company()
    {
        return $this->belongsTo(Company::class, 'CompanyID');
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image_path)) {
            return asset('images/placeholder.jpg'); // Or default image path
        }

        return asset('storage/' . $this->image_path); // Adjust path if different

    }



}
