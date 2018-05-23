<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

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
        $isEnrolled = $user ? !!$this->users->where('id', $user->id)->first() : false;
        $isReviewedByUser = false;

        $finalExamQuestions = $this->exam->questions->all();
        shuffle($finalExamQuestions);
        $finalExam = [];
        foreach ($finalExamQuestions as $finalExamQuestion) {
            $finalExam['questions'][] = new QuestionResource($finalExamQuestion);
        }

        $reviews = [];
        foreach ($this->usersThatReviewed()->orderBy('created_at', 'desc')->get() as $u) {

            $review = [
                'score' => $u->pivot->score,
                'text' => $u->pivot->text,
                'created_at' => $u->pivot->created_at,
                'user' => new UserResource($u),
            ];

            array_push($reviews, $review);

            if($user && $u->id == $user->id) {
                $isReviewedByUser = true;
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'price' => $this->price,
            'image_url' => $this->image_url,
            'teacher' => new UserResource($this->teacher),
            'isEnrolled' => $isEnrolled,
            'isReviewedByUser' => $isReviewedByUser,
            'reviews' => $reviews,
            'finalExam' => $finalExam,
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
