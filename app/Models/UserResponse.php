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
        'module_question_id',
        'answer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Define relationship with users table
    }

    public function question()
    {
        return $this->belongsTo(ModuleQuestion::class); // Define relationship with module_questions table
    }

    public $module_question_id;

    
}
