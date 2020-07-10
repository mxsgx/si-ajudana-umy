<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AttachmentSubmission extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attachment_id', 'submission_id', 'file_name',
        'name',
    ];
}
