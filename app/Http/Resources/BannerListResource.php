<?php

namespace App\Http\Resources;

use App\Models\Count;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerListResource extends JsonResource
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
            'banner_uuid'     => $this->banner_uuid,
            'banner_module'   => $this->banner_module,
            'assets'          => AssetsResource::collection($this->assets),
            'url'             => json_decode($this->url),
            'home_view_count' => Count::where('name', 'home_view')->first()->value ?? null,
        ];
    }
}
