<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Document
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int|null $section_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Section|null $section
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereUrl($value)
 * @mixin \Eloquent
 */
class Document extends Model
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
