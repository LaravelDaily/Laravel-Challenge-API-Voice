<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Voice extends Pivot
{
    protected $fillable = [
    	'question_id',
    	'user_id',
    	'value',
    ];
}
