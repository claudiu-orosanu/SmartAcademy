<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = auth()->user();
        $is_enrolled = $user ? !!$this->users->where('id', $user->id)->first() : false;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'price' => $this->price,
            'image_url' => $this->image_url,
            'teacher_id' => $this->teacher_id,
            'is_enrolled' => $is_enrolled,
            'sections' => SectionResource::collection($this->sections),
        ];

        /*return [
            'data' =>
            [
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description,
                'category' => $this->category,
            ],
            'links' => [
                'self' => 'link-value',
            ],
        ];*/

    }
}
