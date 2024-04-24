<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

    protected $fillable = [ //TODO: Define relations for models
        //'CUID',
        'UserID',
        'CompanyID',
        'isAdmin',
    ];

    //TODO: Define relations for models with modules
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

    //Generate a new ULID for the model
    public static function generateUlid()
    {
        return Str::ulid();
    }

    //Get the ULID attribute
    protected function getUlidAttribute() {
        return Ulid::fromString($this->attributes['CUID']);
    }

    //Logging model changes
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['UserID', 'CompanyID', 'isAdmin']);
    }

}
