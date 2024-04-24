<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Thiagoprz\CompositeKey\HasCompositeKey;

class CompanyUser extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'companyusers';

    protected $primaryKey = ['UserID', 'CompanyID'];

    public $incrementing = false;

    protected $fillable = [ 
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
}
