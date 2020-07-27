<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    public $statuses = [
        'unauthorized' => 'Belum Diotorisasi Kapordi',
        'authorized' => 'Sudah Diotorisasi Kaprodi',
        'authorized-co-dean' => 'Diketahui oleh Wakil Dekan 2',
        'revision-co-dean' => 'Direvisi oleh Wakil Dekan 1',
        'approved-co-dean' => 'Disetujui oleh Wakil Dekan 1',
        'rejected-co-dean' => 'Ditolak oleh Wakil Dekan 1',
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
        'authorized_by',
        'authorized_by_co_dean',
        'approved_by_co_dean',
        'approved_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_start' => 'date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    /**
     * @param $value
     * @return \Illuminate\Support\Optional|mixed
     */
    public function getDateEndAttribute($value)
    {
        return optional($value, function ($date) {
            return Carbon::createFromFormat('Y-m-d', $date);
        });
    }

    /**
     * @param $value
     * @return \Illuminate\Support\Optional|mixed
     */
    public function getTimeStartAttribute($value)
    {
        return optional($value, function ($time) {
            return Carbon::createFromFormat('H:i:s', $time);
        });
    }

    /**
     * @param $value
     * @return \Illuminate\Support\Optional|mixed
     */
    public function getTimeEndAttribute($value)
    {
        return optional($value, function ($time) {
            return Carbon::createFromFormat('H:i:s', $time);
        });
    }

    /**
     * @return array
     */
    public function getFinancialIdsAttribute()
    {
        $ids = [];

        $this->financials()->each(function ($arr) use (&$ids) {
            $ids[] = $arr->id;
        });

        return $ids;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function financials()
    {
        return $this->belongsToMany(Financial::class);
    }

    /**
     * @return array
     */
    public function getFinancialValuesAttribute()
    {
        $values = [];

        $this->financials()->withPivot(['amount'])->each(function ($arr) use (&$values) {
            $values[$arr->id] = $arr->pivot->amount;
        });

        return $values;
    }

    /**
     * @return array
     */
    public function getParticipantIdsAttribute()
    {
        $ids = [];

        $this->participants()->each(function ($arr) use (&$ids) {
            $ids[] = $arr->id;
        });

        return $ids;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participants()
    {
        return $this->belongsToMany(Participant::class);
    }

    /**
     * @return array
     */
    public function getAttachmentsAttribute()
    {
        $ids = [];

        $this->attachments()->withPivot(['file_name', 'name', 'id'])->each(function ($arr) use (&$ids) {
            $ids[$arr->id] = $arr->pivot;
        });

        return $ids;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attachments()
    {
        return $this->belongsToMany(Attachment::class);
    }

    /**
     * @return string
     */
    public function getReadableDatetimeAttribute()
    {
        $str = $this->date_start->translatedFormat('d F Y');

        if ($this->time_start) {
            $str .= ' pukul '.$this->time_start->format('H:i').' WIB';
        }

        if ($this->date_end) {
            $str .= ' s.d '.$this->date_end->translatedFormat('d F Y');
        }

        if ($this->time_end) {
            $str .= ' pukul '.$this->time_end->format('H:i').' WIB';
        }

        return $str;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function authorized()
    {
        return $this->belongsTo(User::class, 'authorized_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function authorizedByCoDean()
    {
        return $this->belongsTo(User::class, 'authorized_by_co_dean', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvedByCoDean()
    {
        return $this->belongsTo(User::class, 'approved_by_co_dean', 'id');
    }
}
