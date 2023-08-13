<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopListResource extends JsonResource
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
            'shops_uuid'      => $this->shops_uuid,
            'category'        => ShopCategoryResource::make($this->category),
            'name'            => $this->name,
            'shops_code'      => $this->shops_code,
            'phone'           => $this->phone,
            'facebook'        => $this->facebook,
            'instagram'       => $this->instagram,
            'position'        => $this->position,
            'addresses'       => $this->addresses->pluck('address') ?? null,
            'remarks'         => $this->remarks,
            'desc'            => $this->desc,
            'payment_methods' => json_decode($this->payment_methods),
            'views'           => $this->display_views,
            'actual_views'    => $this->views,
            'updated_at'      => date('Y-m-d', strtotime($this->updated_at)),
            'rating'          => [
                'overall'               => $this->rating,
                'product_desc'          => $this->product_desc,
                'services_quality'      => $this->services_quality,
                'product_categories'    => $this->product_categories,
                'logistic_services'     => $this->logistic_services,
                'geographical_location' => $this->geographical_location,
            ],
            'image'           => new AssetsResource($this->assets()->where('type', 'main_image')->first()),
        ];
    }
}
