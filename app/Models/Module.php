<?php

/* Module.php */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'modules';
    protected $fillable = ['title', 'image_path', 'CompanyID'];

    // Define the relationship with the Company model
    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class, 'CompanyID');
    }

    //Relationship with the AssignedModule model
    public function assignedModule() : HasOne
    {
        return $this->hasOne(AssignedModule::class, 'ModuleID', 'id');
    }


    public function getImageUrlAttribute()
    {
        if (empty($this->image_path)) {
            return asset('images/placeholder.jpg'); // Or default image path
        }

        return asset('storage/' . $this->image_path); // Adjust path if different

    }



}
