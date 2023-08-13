<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogListResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $blog = Blog::find($request->id);
        // $assets = Assets::where('module_uuid', $blog->blog_uuid)->get();
        return [
            // 'id' => $this->id,
            'blog_uuid' => $this->blog_uuid,
            'blog_categories_uuid' => $this->blog_categories_uuid,
            // 'blog_categories_name' => $blog->category->name,
            'title' => $this->title,
            'code' => $this->code,
            'date' => date('Y-m-d', strtotime($this->date)),
            'short_desc' => $this->short_desc,
            // 'description' => $this->description,
            'top_blog' => $this->top_blog,
            'status' => $this->display_status,
            'views' => $this->views,
            'assets' => AssetsResource::collection($this->assets)->first(),
            // only get first image for thumbnail
        ];
    }
}