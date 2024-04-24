<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;
    
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
}
