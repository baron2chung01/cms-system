<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RenovateCoursePageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'course_categories_uuid' => $this->course_categories_uuid,
            'parents_uuid' => $this->parents_uuid,
            'code' => $this->code,
            'name' => $this->name,
            'courses' => RenovateCourseListResource::collection($this->courses),
        ];
    }
}