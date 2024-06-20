<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswerHistory extends Model
{
    use SoftDeletes;

    protected $table = 'answerhistory';

    protected $primaryKey = 'HistoryID';

    protected $fillable = ['AnswerID', 'UserID', 'CompanyID', 'PostID', 'content', 'is_anonymous'];

    // Define relationships with other models
    public function answer()
    {
        return $this->belongsTo(Answer::class, 'AnswerID', 'AnswerID');
    }

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
