<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class CompanyUser extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'companyusers';

    protected $primaryKey = ['UserID', 'CompanyID'];

    public $incrementing = false;

    protected $fillable = [ //TODO: Define relations for models
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
}
