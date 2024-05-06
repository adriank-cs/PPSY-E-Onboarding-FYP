<?php

namespace App\Models;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Chapter extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'chapters';

    protected $fillable = [
        'title',
        'module_id',
        'description',
    ];

    //Relationship with Module model
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    //Relationship with Item model
    public function item() : HasMany
    {
        return $this->hasMany(Item::class, 'chapter_id', 'id');
    }

    //Relationship with Chapter Progress model
    public function chapterProgress() : HasOne
    {
        return $this->hasOne(ChapterProgress::class, 'ChapterID', 'id');
    }

    //Logging Model Changes
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'module_id', 'description']);
    }
}