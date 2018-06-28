<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ExamResource extends JsonResource
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
        $userAnswers = DB::table('exam_results')
            ->where('user_id', $user->id)
            ->where('exam_id', $this->id)
            ->get();

        return [
            'id' => $this->id,
            'questions' => QuestionResource::collection($this->questions),
            'userAnswers' => $userAnswers
        ];

    }
}
