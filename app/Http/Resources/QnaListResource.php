<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QnaListResource extends JsonResource
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
            'qna_uuid' => $this->qna_uuid,
            'qna_categories_uuid' => $this->qna_categories_uuid,
            'title' => $this->title,
            'date' => date('Y-m-d', strtotime($this->date)),
            'short_desc' => $this->short_desc,
            'description' => $this->description,
            'Show' => false,
            // 'status' => $this->display_status,
        ];
    }
}
