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
        $user = auth()->user();
        $userIsEnrolled = false;
        $enrollment = \DB::table('enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $this->course->id)
            ->get();
        if($enrollment->isNotEmpty()) {
            $userIsEnrolled = true;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'order_number' => $this->order_number,
            $this->mergeWhen(auth()->check() && $userIsEnrolled, [
                'videos' => VideoResource::collection($this->videos),
                'documents' => DocumentResource::collection($this->documents),
                'exams' => ExamResource::collection($this->exams),
            ]),
        ];

    }
}
