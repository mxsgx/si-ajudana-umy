<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'nip', 'nik', 'birth_date', 'birth_place',
        'study_id', 'address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'birth_date' => 'date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id', 'id');
    }

    public function getBirthAttribute()
    {
        $birth = '';

        if ($this->birth_place) {
            $birth .= $this->birth_place . ', ';
        }

        $birth .= $this->birth_date->translatedFormat('d/m/Y');

        return $birth;
    }
}
