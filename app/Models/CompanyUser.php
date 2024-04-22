<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thiagoprz\CompositeKey\HasCompositeKey;

class CompanyUser extends Model
{
    use HasFactory, HasUlids, SoftDeletes, HasCompositeKey;

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

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    // Relationship with the Company model
    public function company()
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

}
