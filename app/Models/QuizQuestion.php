<?php
/* QuizQuestion.php */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'type',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class); // Define relationship with modules table
    }
    public function userResponses()
    {
        // Define the relationship with user_responses table:
        return $this->hasMany(UserResponse::class);
    }
}
