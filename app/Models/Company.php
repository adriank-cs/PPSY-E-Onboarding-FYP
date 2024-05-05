<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'companies';

    protected $primaryKey = 'CompanyID';

    protected $fillable = [
        'CompanyID',
        'Name',
        'Industry',
        'Address',
        'Website',
    ];

    //Relationship
    public function companyUser() : HasOne
    {
        return $this->hasOne(CompanyUser::class, 'CompanyID');
    }

    //Relationship with the Module model
    public function module() : HasMany
    {
        return $this->hasMany(Module::class, 'CompanyID', 'CompanyID');
    }
}
