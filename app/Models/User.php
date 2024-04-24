<?php
/* User.php */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserResponse;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->companyUser && $this->companyUser->isAdmin;
    }

    public function isEmployee()
    {
        return $this->companyUser && !$this->companyUser->isAdmin;
    }

    public function isSuperadmin()
    {
        return $this->superadmin()->exists();
    }

    //Relationships

    public function companyUser() : HasOne
    {
        return $this->hasOne(CompanyUser::class, 'UserID');
    }

    public function profile() : HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
    
    public function superadmin() : HasOne
    {
        return $this->hasOne(Superadmin::class, 'UserID');
    }

    //TODO: Might be wrong
    public function responses()
    {
        return $this->hasMany(UserResponse::class);
    }

}
