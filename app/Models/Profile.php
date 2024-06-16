<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Profile extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'profiles';
    protected $primaryKey = 'profile_id';


    protected $fillable = [
        'profile_id',
        'user_id',
        'employee_id',
        'name',
        'gender',
        'dob',
        'age',
        'position',
        'dept',
        'bio',
        'phone_no',
        'address',
        'profile_picture',
        'subscription_ends_at', //Added by Alda for Subscription Status
    ];

    // Accessor to get the full URL of the profile picture
    public function getProfilePictureUrlAttribute()
    {
        return $this->attributes['profile_picture']
            ? asset('storage/' . $this->attributes['profile_picture'])
            : null;
    }


    // Mutator to store the profile picture in storage
    public function setProfilePictureAttribute($file)
    {
        $this->attributes['profile_picture'] = $file
        ? Storage::disk('public')->put('profile_pictures', $file)
        : null;
    }

    //Relationship
    function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Logging model changes
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->dontLogIfAttributesChangedOnly(['updated_at', 'created_at'])
            ->setDescriptionForEvent(fn(string $eventName) => "User profile {$eventName}"); //User profile updated/deleted/created
    }

}


