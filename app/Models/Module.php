<?php

/* Module.php */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; //TODO: Check if this directive is required

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'image_path'];

    /**
     * Get the path to the image file.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if (empty($this->image_path)) {
            return asset('images/placeholder.jpg'); // Or default image path
        }

        // Use Storage if you're saving images in storage:
        //return Storage::disk('local')->url($this->image_path);

        return asset('storage/' . $this->image_path); // Adjust path if different

    }
    public function questions()
    {
        return $this->hasMany(ModuleQuestion::class); // Define the relationship: module has many questions
    }
}
