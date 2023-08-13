<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCategoryResource extends JsonResource
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
            'shop_categories_uuid' => $this->shop_categories_uuid,
            'parents_uuid'         => $this->display_parents,
            'code'                 => $this->code,
            'name'                 => $this->name,
            'status'               => $this->display_status,
        ];
    }
}