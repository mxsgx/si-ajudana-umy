<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Registered user roles.
     *
     * @var string[]
     */
    public $roles = [
        'admin' => 'Admin',
        'dean' => 'Dekanat',
        'head-of-program-study' => 'Kaprodi',
        'lecturer' => 'Dosen',
        'co-dean-1' => 'Wakil Dekan 1',
        'co-dean-2' => 'Wakil Dekan 2',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'study_id',
        'lecturer_id', 'faculty_id', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }

    /**
     * @return string
     */
    public function getRoleNameAttribute()
    {
        return $this->roles[$this->role];
    }
}
