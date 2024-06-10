<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AssignedModule extends Model
{
    use HasFactory, SoftDeletes, HasCompositeKey, LogsActivity;

    protected $table = 'assigned_module';

    //Composite primary keys
    protected $primaryKey = ['UserID', 'CompanyID', 'ModuleID'];

    protected $fillable = [ 
        'UserID',
        'CompanyID',
        'ModuleID',
        'DateAssigned',
        'due_date',
    ];

    // Relationship with the Company User model
    public function companyUser() : BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, ['UserID', 'CompanyID'], ['UserID', 'CompanyID']); //Correct due to composite keys library
    }

    // Relationship with the Module model
    public function module() : BelongsTo
    {
        return $this->belongsTo(Module::class, 'ModuleID');
    }

    // Relationship with the Chapter Progress model
    public function chapterProgress() : HasOne
    {
        return $this->hasOne(ChapterProgress::class, ['UserID', 'CompanyID', 'ModuleID'], ['UserID', 'CompanyID', 'ModuleID']);
    }

    //Relationship with the Item Progress model
    public function itemProgress() : HasOne
    {
        return $this->hasOne(ItemProgress::class, ['UserID', 'CompanyID', 'ModuleID'], ['UserID', 'CompanyID', 'ModuleID']);
    }

    //Logging model changes
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['UserID', 'CompanyID', 'ModuleID', 'DateAssigned']);
    }
}
