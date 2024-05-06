<?php
/* UserResponse.php */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_question_id',
        'answer', // Store all answers as JSON string
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Define relationship with users table
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class); // Define relationship with module_questions table
    }

    public $quiz_question_id;

    
}
