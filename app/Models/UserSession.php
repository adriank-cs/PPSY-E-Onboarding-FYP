<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;

class UserSession extends Model
{
    use HasFactory, SoftDeletes;

    //Table
    protected $table = 'user_session';

    public $incrementing = false;

    //Primary Key
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'UserID',
        'first_activity_at',
        'last_activity_at',
        'duration',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    //Casts
    protected $casts = [
        'first_activity_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    //Generate a new ULID for the model
    public static function generateUlid()
    {
        return Str::ulid();
    }
    //Get the ULID attribute
    protected function getUlidAttribute() {
        return Ulid::fromString($this->attributes['id']);
    }

    //Relationship with the User model
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

}
