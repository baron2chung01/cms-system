<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopShopCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $chunks = TopShopResource::collection($this->topShops)->chunk(6);
        $chunks = $chunks->map(function ($chunk) {
            return $chunk = $chunk->values();
        });

        return [
            'id'                   => $this->id,
            'shop_categories_uuid' => $this->shop_categories_uuid,
            'name'                 => $this->name,
            'shops'                => $chunks,
        ];
    }
}
