<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RenovateCourseDetailResource extends JsonResource
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
            'renovate_courses_uuid' => $this->renovate_courses_uuid,
            'course_categories_uuid' => $this->course_categories_uuid,
            'title' => $this->title,
            'name' => $this->name,
            'code' => $this->code,
            'short_desc' => $this->short_desc,
            'description' => $this->description,
            'location' => $this->location,
            'assets' => AssetsResource::collection($this->assets),
        ];
    }
}