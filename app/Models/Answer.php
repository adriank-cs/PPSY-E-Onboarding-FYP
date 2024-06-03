<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory, HasCompositeKey, SoftDeletes;

    protected $table = 'answer';
    protected $primaryKey = 'AnswerID';

    protected $fillable = ['UserID', 'CompanyID', 'PostID', 'content', 'is_anonymous'];

    // Define relationships with other models
    public function user()
    {
        return $this->belongsTo(CompanyUser::class, 'UserID', 'UserID');
    }

    public function companyUser()
    {
        return $this->belongsTo(CompanyUser::class, 'CompanyID', 'CompanyID');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'PostID', 'PostID');
    }
}
