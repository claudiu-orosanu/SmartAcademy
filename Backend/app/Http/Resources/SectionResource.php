<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
            'order_number' => $this->order_number,
            $this->mergeWhen(auth()->check(), [
                'videos' => VideoResource::collection($this->videos),
                'documents' => DocumentResource::collection($this->documents),
                'exams' => ExamResource::collection($this->exams),
            ]),
        ];

    }
}
