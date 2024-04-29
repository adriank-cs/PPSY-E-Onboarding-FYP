<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Item extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'companyusers';

    protected $primaryKey = 'id';

    protected $fillable = [ 
        'chapter_id',
        'title',
        'description',
        'content',
        'due_date',
    ];

    //Relationship with Chapter model
    public function chapter() : BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'id');
    }

    //Relationship with Item Progress model
    public function itemProgress() : HasOne
    {
        return $this->hasOne(ItemProgress::class, 'ItemID', 'id');
    }

    //Logging Model Changes
    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['chapter_id', 'title', 'description', 'content', 'due_date']);
    }
}
