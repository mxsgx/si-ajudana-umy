<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    public $statuses = [
        'unauthorized' => 'Belum Diotorisasi Kapordi',
        'authorized' => 'Sudah Diotorisasi Kaprodi',
        'approved' => 'Disetujui Dekan',
        'rejected' => 'Ditolak Dekan',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lecturer_id',
        'activity_id',
        'status',
        'name',
        'date_start',
        'date_end',
        'place',
        'note',
        'title',
        'time_start',
        'time_end',
        'writer',
        'schema',
        'grant',
        'category_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_start' => 'date'
    ];

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function getDateEndAttribute($value)
    {
        return optional($value, function ($date) {
            return Carbon::createFromFormat('Y-m-d', $date);
        });
    }

    public function getTimeStartAttribute($value)
    {
        return optional($value, function ($time) {
            return Carbon::createFromFormat('H:i:s', $time);
        });
    }

    public function getTimeEndAttribute($value)
    {
        return optional($value, function ($time) {
            return Carbon::createFromFormat('H:i:s', $time);
        });
    }

    public function getFinancialIdsAttribute()
    {
        $ids = [];

        $this->financials()->each(function ($arr) use (&$ids) {
            $ids[] = $arr->id;
        });

        return $ids;
    }

    public function getFinancialValuesAttribute()
    {
        $values = [];

        $this->financials()->withPivot(['amount'])->each(function ($arr) use (&$values) {
            $values[$arr->id] = $arr->pivot->amount;
        });

        return $values;
    }

    public function getParticipantIdsAttribute()
    {
        $ids = [];

        $this->participants()->each(function ($arr) use (&$ids) {
            $ids[] = $arr->id;
        });

        return $ids;
    }

    public function getAttachmentsAttribute()
    {
        $ids = [];

        $this->attachments()->withPivot(['file_name', 'name', 'id'])->each(function ($arr) use (&$ids) {
            $ids[$arr->id] = $arr->pivot;
        });

        return $ids;
    }

    public function getReadableDatetimeAttribute()
    {
        $str = $this->date_start->translatedFormat('d F Y');

        if ($this->time_start) {
            $str .= ' '.$this->time_start->format('H:i');
        }

        if ($this->date_end) {
            $str .= ' s.d '.$this->date_end->translatedFormat('d F Y');
        }

        if ($this->time_end) {
            $str .= ' '.$this->time_end->format('H:i');
        }

        return $str;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attachments()
    {
        return $this->belongsToMany(Attachment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function financials()
    {
        return $this->belongsToMany(Financial::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participants()
    {
        return $this->belongsToMany(Participant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachmentSubmissions()
    {
        return $this->hasMany(AttachmentSubmission::class);
    }
}
