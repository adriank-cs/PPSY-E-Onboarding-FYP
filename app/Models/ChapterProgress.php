<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ChapterProgress extends Model
{
    use HasFactory, HasCompositeKey, LogsActivity;

    protected $table = 'chapters_progress';

    //Composite primary keys
    protected $primaryKey = ['UserID', 'CompanyID', 'ModuleID', 'ChapterID'];

    protected $fillable = [ 
        'UserID',
        'CompanyID',
        'ModuleID',
        'ChapterID',
        'IsCompleted',
    ];

    //Relationship with Assigned Module model
    public function assignedModule() : BelongsTo
    {
        return $this->belongsTo(AssignedModule::class, ['UserID', 'CompanyID', 'ModuleID'], ['UserID', 'CompanyID', 'ModuleID']);
    }

    //Relationship with Chapter model
    public function chapter() : BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'ChapterID', 'id');
    }

    //Logging Model Changes
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['UserID', 'CompanyID', 'ModuleID', 'ChapterID', 'IsCompleted']);
    }
}
