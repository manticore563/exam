<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['question_id', 'text', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
?>
