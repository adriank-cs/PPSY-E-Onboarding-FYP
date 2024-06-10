<?php
/* User.php */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserResponse;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_active_session',
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

    // Relationships

    // Relationship with CompanyUser model
    public function companyUser() : HasOne
    {
        return $this->hasOne(CompanyUser::class, 'UserID');
    }

    // Relationship with Profile model
    public function profile() : HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
    
    // Relationship with Superadmin model
    public function superadmin() : HasOne
    {
        return $this->hasOne(Superadmin::class, 'UserID');
    }

    // Relationship with UserSession model
    public function userSession() : HasMany 
    {
        return $this->hasMany(UserSession::class, 'UserID');
    }

    // Relationship with UserResponse model
    public function responses()
    {
        return $this->hasMany(UserResponse::class);
    }

    // Logging model changes
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'password'])
            ->dontLogIfAttributesChangedOnly(['updated_at', 'created_at', 'last_active_session'])
            ->setDescriptionForEvent(fn(string $eventName) => "User account {$eventName}");
    }

    // Relationship with UserQuizAttempt model
    public function quizAttempts()
    {
        return $this->hasMany(UserQuizAttempt::class);
    }

    // Relationship with Company model through companyusers pivot table
    public function company()
    {
        return $this->belongsToMany(Company::class, 'companyusers', 'UserID', 'CompanyID');
    }

    

}
