<?php

namespace App\Http\Resources;

use App\Http\Resources\AssetsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'qty'          => $this->qty,
            'price'        => $this->price,
            'description'  => $this->description,
            'main_product' => $this->main_product,
            'image'        => count($this->images) ? AssetsResource::make($this->images[0]) : null,
        ];
    }
}
