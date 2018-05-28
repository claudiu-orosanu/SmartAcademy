<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Course
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $category
 * @property string $image
 * @property float $price
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $imageUrl
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Section[] $sections
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereImageUrl($value)
 * @property string|null $image_url
 * @property-read \App\Exam $exams
 * @property int|null $teacher_id
 * @property-read \App\Exam $exam
 * @property-read \App\User|null $teacher
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $usersThatReviewed
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereTeacherId($value)
 */
class Course extends Model
{
    public static $courseCategories = [
        'Data science' => 0,
        'Computer science' => 1,
        'Math' => 2
    ];

    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'image_url',
        'teacher_id',
    ];

    // relationships

    /**
     * The sections for this course.
     */public function sections() {
        return $this->hasMany('App\Section');
    }


    /**
     * The exam for this course.
     */
    public function exam() {
        return $this->hasOne('App\Exam');
    }

    /**
     * The creator of this course.
     */
    public function teacher()
    {
        return $this->belongsTo('App\User', 'teacher_id', 'id');
    }

    /**
     * The users that are enrolled in this course.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'enrollments', 'course_id', 'user_id')
            ->withPivot('is_completed')
            ->withTimestamps();
    }

    /**
     * The users that are enrolled in this course.
     */
    public function usersThatReviewed()
    {
        return $this
            ->belongsToMany('App\User', 'reviews', 'course_id', 'user_id')
            ->withPivot('score', 'text')
            ->withTimestamps();
    }

}
