<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopShopResource extends JsonResource
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
            'shops_uuid' => $this->shops_uuid,
            'name'       => $this->name,
            'address'    => $this->addresses->pluck('address')->first() ?? null,
            'phone'      => $this->phone,
            'whatsapp'   => $this->whatsapp,
            'rating'     => $this->rating,
            'views'      => $this->views,
        ];
    }
}
