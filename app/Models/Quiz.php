<?php

/* Quiz.php */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'attempt_limit', 
        'company_id'
    ];

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class); // Define the relationship: module has many questions
    }

    // add by Aifei
    public function attempts()
    {
        return $this->hasMany(UserQuizAttempt::class);
    }

    // add relationship with company for quiz
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
