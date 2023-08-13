<?php

namespace App\Http\Resources;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopsReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (!isset($this->created_by)) {
            $clientCol = null;
        } else {
            $clientCol = ClientResource::collection(Client::where('clients_uuid', $this->created_by)->get());
        }
        return [
            'client'             => $clientCol,
            'shops_reviews_uuid' => $this->shops_reviews_uuid,
            'shops_uuid'         => $this->shops_uuid,
            'comment'            => $this->comment,
            'created_at'         => date('Y-m-d', strtotime($this->created_at)),
            'updated_at'         => date('Y-m-d', strtotime($this->updated_at)),
            'rating'             => [
                'overall'               => $this->rating,
                'product_desc'          => $this->product_desc,
                'services_quality'      => $this->services_quality,
                'product_categories'    => $this->product_categories,
                'logistic_services'     => $this->logistic_services,
                'geographical_location' => $this->geographical_location,
            ],

        ];
    }
}