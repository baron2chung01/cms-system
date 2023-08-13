<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (!isset($this->client)) {
            $avatar = null;
        }
        else {
            if (!isset($this->client->image)) {
                $avatar = null;
            }
            else {
                $avatar = AssetsResource::make($this->client->image);
            }
        }

        return [
            'id' => $this->id,
            'members_uuid' => $this->members_uuid,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->display_status,
            'type' => $this->display_type,
            'avatar' => $avatar,
        ];
    }
}
