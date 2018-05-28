<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Question
 *
 * @property int $id
 * @property string $text
 * @property int|null $exam_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Answer[] $answers
 * @property-read \App\Exam|null $exam
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Exam[] $exams
 */
class Question extends Model
{
    protected $fillable =[
        'text',
        'exam_id',
    ];

    // relationships

    /**
     * The exams that this question belongs to.
     */
    public function exams()
    {
        return $this->belongsToMany('App\Exam', 'exam_questions', 'question_id', 'exam_id');
    }

    /**
     * The answers for this questions.
     */
    public function answers() {
        return $this->hasMany('App\Answer');
    }
}
