<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CompanyUser extends Model
{
    use HasFactory, SoftDeletes, HasCompositeKey, LogsActivity;

    protected $table = 'companyusers';

    //protected $primaryKey = 'CUID';
    protected $primaryKey = ['UserID', 'CompanyID'];

    public $incrementing = false;

    protected $fillable = [ 
        //'CUID',
        'UserID',
        'CompanyID',
        'isAdmin',
    ];

    // Relationship with the User model
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    // Relationship with the Company model
    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class, 'CompanyID', 'id');
    }

    //Relationship with Assigned Module model
    public function assignedModule() : HasMany
    {
        return $this->hasMany(AssignedModule::class, ['UserID', 'CompanyID'], ['UserID', 'CompanyID']);
    }

    //Logging model changes
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['UserID', 'CompanyID', 'isAdmin']);
    }

}
