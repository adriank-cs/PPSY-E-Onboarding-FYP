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
        'item_id',
        'passing_score',
    ];

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class); // Define the relationship: module has many questions
    }

    // add relationship with company for quiz
    public function item()
    {
        return $this->belongsTo(item::class);
    }

}
