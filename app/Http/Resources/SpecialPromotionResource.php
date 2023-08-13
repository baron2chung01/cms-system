<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecialPromotionResource extends JsonResource
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
            'special_promotion_uuid' => $this->special_promotion_uuid,
            'name' => $this->name,
            'url' => $this->url,
            'image' => new AssetsResource($this->image),
        ];
    }
}
