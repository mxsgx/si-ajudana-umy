<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'faculty_id',
    ];
}
