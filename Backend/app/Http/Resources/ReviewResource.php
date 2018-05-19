<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'score' => $this->score,
            'text' => $this->text,
            'created_at' => $this->created_at,
            'user' => new UserResource(User::find($this->user_id)),
        ];

    }
}
