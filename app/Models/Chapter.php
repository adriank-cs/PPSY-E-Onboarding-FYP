<?php

namespace App\Models;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    /**
     * Get the module that owns the chapter.
     */
    protected $fillable = ['module_id', 'title'];
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}