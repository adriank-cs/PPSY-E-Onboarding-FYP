<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, HasCompositeKey, SoftDeletes;

    protected $table = 'post';

    protected $primaryKey = ['PostID'];

    protected $fillable = [ //TODO: Fix atrributes to match the database table
        'UserID',
        'CompanyID',
        'title',
        'content',
        'is_answered',
        'is_locked',
        'is_archived',
        'is_anonymous',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    /**
     * Get the company that the post belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'CompanyID');
    }
}
