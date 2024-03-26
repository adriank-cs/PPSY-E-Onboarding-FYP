<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'postid',
        'userid',
        'companyid',
        'title',
        'content',
        'is_answered',
        'is_locked',
        'is_archived',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    /**
     * Get the company that the post belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'companyid');
    }
}

