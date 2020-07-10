<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ParticipantSubmission extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'participant_id', 'submission_id',
    ];
}
