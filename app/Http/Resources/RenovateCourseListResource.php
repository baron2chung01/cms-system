<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RenovateCourseListResource extends JsonResource
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
            'renovate_courses_uuid'  => $this->renovate_courses_uuid,
            'course_categories_uuid' => $this->course_categories_uuid,
            'title'                  => $this->title,
            'name'                   => $this->name,
            'instructor_name'        => $this->instructor_name,
            'code'                   => $this->code,
            'short_desc'             => $this->short_desc,
            'description'            => $this->description,
            'location'               => $this->location,
            'url'                    => $this->url,
            'date'                   => $this->date,
            'price'                  => number_format($this->price, 2),
            'discounted_price'       => number_format($this->discounted_price, 2),
            'location'               => $this->location,
            'assets'                 => AssetsResource::collection($this->assets)->first(), // show first image only
        ];
    }
}
