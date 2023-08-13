<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecialPromotionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $result = [
            'special_promotion_uuid' => $this->special_promotion_uuid,
            'code'                   => $this->code,
            'name'                   => $this->name,
            'description'            => $this->description,
            'url'                    => $this->url,
            'status'                 => $this->status,
            'image'                  => AssetsResource::make($this->image),
        ];
        if (isset($this->receipts)) {
            return array_merge($result, ['receipts' => $this->receipts]);
        }
        else {
            return $result;
        }
    }
}
