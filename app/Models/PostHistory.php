<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostHistory extends Model
{
    use SoftDeletes;

    protected $table = 'posthistory';

    protected $primaryKey = 'HistoryID';

    protected $fillable = [
        'PostID', 'UserID', 'CompanyID', 'title', 'content', 
        'is_answered', 'is_locked', 'is_archived', 'is_anonymous'
    ];

    // Define relationships with other models
    public function post()
    {
        return $this->belongsTo(Post::class, 'PostID', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'CompanyID', 'CompanyID');
    }
}
