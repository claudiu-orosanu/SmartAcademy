<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Exam
 *
 * @property int $id
 * @property int|null $section_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Question[] $questions
 * @property-read \App\Section|null $section
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereIsFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $course_id
 * @property-read \App\Course|null $course
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereCourseId($value)
 */
class Exam extends Model
{
    protected $fillable =[
        'course_id',
        'section_id',
    ];

    // relationships
    public function section() {
        return $this->belongsTo('App\Section');
    }

    public function course() {
        return $this->belongsTo('App\Course');
    }
    /**
     * The questions for this exam.
     */
    public function questions()
    {
        return $this->belongsToMany('App\Question', 'exam_questions', 'exam_id', 'question_id');
    }
}
