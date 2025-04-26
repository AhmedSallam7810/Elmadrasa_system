<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AwardStudent extends Pivot
{
    protected $table = 'award_student';

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime'
    ];
}
