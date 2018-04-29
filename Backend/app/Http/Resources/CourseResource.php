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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'image_url' => $this->image_url,
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
