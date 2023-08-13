<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetsResource extends JsonResource
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
            'resource_path'      => str_replace(' ', '%20', asset($this->resource_path)),
            'module_uuid'        => $this->module_uuid,
            'second_module_uuid' => $this->second_module_uuid,
            'file_name'          => $this->file_name,
            'type'               => $this->type,
            'status'             => $this->status,
        ];
    }
}
