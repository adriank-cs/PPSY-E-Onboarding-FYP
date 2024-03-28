<?php
/* ModuleQuestion.php */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'question',
        'type',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class); // Define relationship with modules table
    }
    public function userResponses()
    {
        // Define the relationship with user_responses table:
        return $this->hasMany(UserResponse::class);
    }
}
