<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'CompanyID';

    protected $fillable = [
        'CompanyID',
        'Name',
        'Industry',
        'Address',
    ];
}
