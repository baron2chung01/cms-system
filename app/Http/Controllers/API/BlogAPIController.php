<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateBlogAPIRequest;
use App\Http\Requests\API\UpdateBlogAPIRequest;
use App\Http\Resources\BlogDetailResource;
use App\Http\Resources\BlogListResource;
use App\Http\Resources\BlogPageResource;
use App\Http\Resources\NewestBlogResource;
use App\Models\Blog;
use App\Repositories\BlogRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class BlogAPIController
 */
class BlogAPIController extends AppBaseController
{
    private BlogRepository $blogRepository;

    public function __construct(BlogRepository $blogRepo)
    {
        $this->blogRepository = $blogRepo;
    }

    /**
     * Display a listing of the Blogs.
     * GET|HEAD /blogs
     */
    public function index(Request $request): JsonResponse
    {
        // filter: keyword, category
        // sort: newest->oldest, oldest->newest, views

        // get filters value from request
        $filterKeyword = $request->get('keyword') ?? ''; // string for title/description keyword
        $filterCategory = $request->get('category') ?? ''; // blog_categories_uuid

        $sortDate = $request->get('date') ?? ''; // ASC or DESC
        $sortViews = $request->get('views') ?? ''; // ASC or DESC

        $blogsQuery = Blog::with('assets')->where('status', Blog::ACTIVE);

        if (!empty($filterCategory)) {
            $blogsQuery = $blogsQuery->where('blog_categories_uuid', $filterCategory);
        }
        if (!empty($filterKeyword)) {
            $blogsQuery = $blogsQuery->where(function ($query) use ($filterKeyword) {
                $query->where('title', 'like', '%' . $filterKeyword . '%');
                $query->orWhere('description', 'like', '%' . $filterKeyword . '%');
            });
        }
        if (!empty($sortDate)) {
            if ($sortDate == "desc") {
                $blogsQuery = $blogsQuery->orderBy('date', 'desc');
            } else {
                $blogsQuery = $blogsQuery->orderBy('date');
            }
        }

        if (!empty($sortViews)) {
            if ($sortViews == "desc") {
                $blogsQuery = $blogsQuery->orderBy('views', 'desc');
            } else {
                $blogsQuery = $blogsQuery->orderBy('views');
            }
        }

        $blogs = $blogsQuery->paginate(10);

        if (empty($blogs->items())) {
            return $this->sendError('Blog not found');
        }

        return $this->sendResponse(new BlogPageResource($blogs), 'Blogs retrieved successfully');
    }

    /**
     * Store a newly created Blog in storage.
     * POST /blogs
     */
    public function store(CreateBlogAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $blog = $this->blogRepository->create($input);

        return $this->sendResponse($blog->toArray(), 'Blog saved successfully');
    }

    /**
     * Display the specified Blog.
     * GET|HEAD /blogs/{id}
     */
    public function show($uuid): JsonResponse
    {
        /** @var Blog $blog */
        $blog = $this->blogRepository->where('blog_uuid', $uuid)->first();

        if (empty($blog) || $blog->status != Blog::ACTIVE) {
            return $this->sendError('Blog not found');
        }

        // dd($blog);

        // increment views
        $blog->update(['views' => $blog->views + 1]);

        return $this->sendResponse(new BlogDetailResource($blog), 'Blog retrieved successfully');
    }

    /**
     * Update the specified Blog in storage.
     * PUT/PATCH /blogs/{id}
     */
    // public function update($id, UpdateBlogAPIRequest $request): JsonResponse
    // {
    //     $input = $request->all();

    //     /** @var Blog $blog */
    //     $blog = $this->blogRepository->find($id);

    //     if (empty($blog)) {
    //         return $this->sendError('Blog not found');
    //     }

    //     $blog = $this->blogRepository->update($input, $id);

    //     return $this->sendResponse($blog->toArray(), 'Blog updated successfully');
    // }

    /**
     * Remove the specified Blog from storage.
     * DELETE /blogs/{id}
     *
     * @throws \Exception
     */
    // public function destroy($id): JsonResponse
    // {
    //     /** @var Blog $blog */
    //     $blog = $this->blogRepository->find($id);

    //     if (empty($blog)) {
    //         return $this->sendError('Blog not found');
    //     }

    //     $blog->delete();

    //     return $this->sendSuccess('Blog deleted successfully');
    // }

    // show blogs of same category, ordered by views desc
    public function showRecommend($uuid)
    {
        $blog = $this->blogRepository->where('blog_uuid', $uuid)->first();

        if (empty($blog) || $blog->status != Blog::ACTIVE) {
            return $this->sendError('Blog not found');
        }

        $recommendBlogs = $blog->category->blogs->where('blog_uuid', '!=', $uuid)->where('status', Blog::ACTIVE)
            ->sortByDesc('views')->take(6);

        return $this->sendResponse(BlogListResource::collection($recommendBlogs), 'Blogs retrieved successfully');

    }

    // show blogs of same category, ordered by views desc
    public function showTop()
    {
        $blogs = Blog::where('status', Blog::ACTIVE)->orderBy('views', 'desc')->take(6)->get();

        if (empty($blogs)) {
            return $this->sendError('Blog not found');
        }

        return $this->sendResponse(BlogListResource::collection($blogs), 'Blogs retrieved successfully');

    }

    public function showNew()
    {
        $blogs = Blog::with('assets')->where('status', Blog::ACTIVE)->where('top_blog', 1)->orderBy('date', 'desc')->get();

        if (empty($blogs)) {
            return $this->sendError('Blog not found');
        }

        return $this->sendResponse(NewestBlogResource::collection($blogs), 'Blogs retrieved successfully');

    }
}
