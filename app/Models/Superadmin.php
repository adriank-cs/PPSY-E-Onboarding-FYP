<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Superadmin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'superadmins'; // Specify the table name if it's different

    protected $primaryKey = 'AdminID'; // Specify the primary key if it's different

    protected $fillable = [
        'AdminID',
        'UserID',
    ];

    // Define any relationships or additional properties here
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID');
    }
}
