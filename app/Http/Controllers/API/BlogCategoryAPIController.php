<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBlogCategoryAPIRequest;
use App\Http\Requests\API\UpdateBlogCategoryAPIRequest;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class BlogCategoryAPIController
 */
class BlogCategoryAPIController extends AppBaseController
{
    private BlogCategoryRepository $blogCategoryRepository;

    public function __construct(BlogCategoryRepository $blogCategoryRepo)
    {
        $this->blogCategoryRepository = $blogCategoryRepo;
    }

    /**
     * Display a listing of the BlogCategories.
     * GET|HEAD /blog-categories
     */
    public function index(): JsonResponse
    {
        $blogCategories = $this->blogCategoryRepository->where('status', BlogCategory::ACTIVE)->get(['name', 'blog_categories_uuid']);

        return $this->sendResponse($blogCategories->toArray(), 'Blog Categories retrieved successfully');
    }

    /**
     * Store a newly created BlogCategory in storage.
     * POST /blog-categories
     */
    public function store(CreateBlogCategoryAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $blogCategory = $this->blogCategoryRepository->create($input);

        return $this->sendResponse($blogCategory->toArray(), 'Blog Category saved successfully');
    }

    /**
     * Display the specified BlogCategory.
     * GET|HEAD /blog-categories/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var BlogCategory $blogCategory */
        $blogCategory = $this->blogCategoryRepository->find($id);

        if (empty($blogCategory)) {
            return $this->sendError('Blog Category not found');
        }

        return $this->sendResponse($blogCategory->toArray(), 'Blog Category retrieved successfully');
    }

    /**
     * Update the specified BlogCategory in storage.
     * PUT/PATCH /blog-categories/{id}
     */
    public function update($id, UpdateBlogCategoryAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var BlogCategory $blogCategory */
        $blogCategory = $this->blogCategoryRepository->find($id);

        if (empty($blogCategory)) {
            return $this->sendError('Blog Category not found');
        }

        $blogCategory = $this->blogCategoryRepository->update($input, $id);

        return $this->sendResponse($blogCategory->toArray(), 'BlogCategory updated successfully');
    }

    /**
     * Remove the specified BlogCategory from storage.
     * DELETE /blog-categories/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var BlogCategory $blogCategory */
        $blogCategory = $this->blogCategoryRepository->find($id);

        if (empty($blogCategory)) {
            return $this->sendError('Blog Category not found');
        }

        $blogCategory->delete();

        return $this->sendSuccess('Blog Category deleted successfully');
    }
}
