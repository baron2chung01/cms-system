<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QnaPageResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "categories_uuid" => $this->categories_uuid,
            "parents_uuid" => $this->parents_uuid,
            "code" => $this->code,
            "name" => $this->name,
            'qnas' => QnaListResource::collection($this->qnas),
            // 'data' => $this->items(),
        ];
    }
}
