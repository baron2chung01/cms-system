<?php

namespace App\Http\Resources;

use App\Models\Shop;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->type == Shop::PRODUCT) { // product mode
            $products = ProductResource::collection($this->activeProducts);
        } else { // decor mode
            // replace the shop remarks to product remarks
            $products = $this->activeProducts->map(function ($item) {
                return [
                    'id'           => $item->id,
                    'name'         => $item->name,
                    'qty'          => $item->qty,
                    'price'        => $item->price,
                    'main_product' => $item->main_product,
                    "description"  => $this->remarks,
                    'image'        => count($item->images) ? AssetsResource::make($item->images[0]) : null,
                ];
            });
        }

        return [
            'list_images'     => new AssetsResource($this->assets()->where('type', 'main_image')->first()),
            'type'            => Shop::TYPE[$this->type],
            'product'         => $products,
            'banner'          => new AssetsResource($this->banner),
            'banner_layer'    => $this->banner_layer,
            'shops_uuid'      => $this->shops_uuid,
            'name'            => $this->name,
            'shops_code'      => $this->shops_code,
            'phone'           => $this->phone,
            'whatsapp'        => $this->whatsapp,
            'whatsapp_url'    => 'https://wa.me/' . $this->whatsapp,
            'facebook'        => $this->facebook,
            'instagram'       => $this->instagram,
            'position'        => $this->position,
            'contact_person'  => $this->contact_person,
            'addresses'       => $this->addresses->map(function ($address) {
                return ['address' => $address->address, 'latitude' => optional($address->latlong)->latitude, 'longitude' => optional($address->latlong)->longitude];
            }) ?? null,
            'desc'            => $this->desc,
            'remarks'         => $this->remarks,
            'payment_methods' => json_decode($this->payment_methods),
            'views'           => $this->views,
            'updated_at'      => date('Y-m-d', strtotime($this->updated_at)),
            'rating'          => [
                'overall'               => $this->rating,
                'product_desc'          => $this->product_desc,
                'services_quality'      => $this->services_quality,
                'product_categories'    => $this->product_categories,
                'logistic_services'     => $this->logistic_services,
                'geographical_location' => $this->geographical_location,
            ],
            'review_count'    => $this->review_count,
            'reviews'         => ShopsReviewResource::collection($this->reviews),

        ];

    }
}
