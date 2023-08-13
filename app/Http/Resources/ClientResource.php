<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'first_name'  => $this->first_name,
            'last_name'   => $this->last_name,
            'username'    => $this->username,
            'client_uuid' => $this->clients_uuid,
            'image'       => new AssetsResource($this->image),
        ];
    }
}