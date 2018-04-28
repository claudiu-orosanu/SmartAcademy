<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Video
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int|null $section_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Section|null $section
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereUrl($value)
 * @mixin \Eloquent
 */
class Video extends Model
{
    protected $fillable =[
        'name',
        'url',
        'section_id',
    ];

    // relationships
    public function section() {
        return $this->belongsTo('App\Section');
    }
}
