<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Answer
 *
 * @property int $id
 * @property int $order_number
 * @property string $text
 * @property bool $is_correct
 * @property int|null $question_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Question|null $question
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Answer extends Model
{
    protected $fillable =[
        'text',
        'order_number',
        'is_correct',
        'question_id',
    ];

    // relationships
    public function question() {
        return $this->belongsTo('App\Question');
    }
}
