<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Section
 *
 * @property int $id
 * @property int $order_number
 * @property string $name
 * @property int|null $course_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Course|null $course
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Section whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Section whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Section whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Section whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Section whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Section whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Exam[] $exams
 */
class Section extends Model
{
    protected $fillable =[
        'order_number',
        'name',
        'course_id',
    ];

    // relationships
    public function course() {
        return $this->belongsTo('App\Course');
    }

    public function videos() {
        return $this->hasMany('App\Video');
    }

    public function documents() {
        return $this->hasMany('App\Document');
    }

    public function exams() {
        return $this->hasMany('App\Exam');
    }
}
