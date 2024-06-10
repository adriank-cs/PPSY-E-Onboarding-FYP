<?php
/* UserResponse.php */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserResponse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'quiz_question_id',
        'answer', // Add answer field to store user's response
    ];


    protected $casts = [
        'answer' => 'array',
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
