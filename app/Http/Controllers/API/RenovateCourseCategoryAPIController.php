<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRenovateCourseCategoryAPIRequest;
use App\Http\Requests\API\UpdateRenovateCourseCategoryAPIRequest;
use App\Models\RenovateCourseCategory;
use App\Repositories\RenovateCourseCategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class RenovateCourseCategoryAPIController
 */
class RenovateCourseCategoryAPIController extends AppBaseController
{
    private RenovateCourseCategoryRepository $renovateCourseCategoryRepository;

    public function __construct(RenovateCourseCategoryRepository $renovateCourseCategoryRepo)
    {
        $this->renovateCourseCategoryRepository = $renovateCourseCategoryRepo;
    }

    /**
     * Display a listing of the RenovateCourseCategories.
     * GET|HEAD /renovate-course-categories
     */
    public function index(): JsonResponse
    {
        $renovateCourseCategories = $this->renovateCourseCategoryRepository->where('status', RenovateCourseCategory::ACTIVE)->get(['name', 'course_categories_uuid']);

        return $this->sendResponse($renovateCourseCategories->toArray(), 'Renovate Course Categories retrieved successfully');
    }

    /**
     * Store a newly created RenovateCourseCategory in storage.
     * POST /renovate-course-categories
     */
    public function store(CreateRenovateCourseCategoryAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $renovateCourseCategory = $this->renovateCourseCategoryRepository->create($input);

        return $this->sendResponse($renovateCourseCategory->toArray(), 'Renovate Course Category saved successfully');
    }

    /**
     * Display the specified RenovateCourseCategory.
     * GET|HEAD /renovate-course-categories/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var RenovateCourseCategory $renovateCourseCategory */
        $renovateCourseCategory = $this->renovateCourseCategoryRepository->find($id);

        if (empty($renovateCourseCategory)) {
            return $this->sendError('Renovate Course Category not found');
        }

        return $this->sendResponse($renovateCourseCategory->toArray(), 'Renovate Course Category retrieved successfully');
    }

    /**
     * Update the specified RenovateCourseCategory in storage.
     * PUT/PATCH /renovate-course-categories/{id}
     */
    public function update($id, UpdateRenovateCourseCategoryAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var RenovateCourseCategory $renovateCourseCategory */
        $renovateCourseCategory = $this->renovateCourseCategoryRepository->find($id);

        if (empty($renovateCourseCategory)) {
            return $this->sendError('Renovate Course Category not found');
        }

        $renovateCourseCategory = $this->renovateCourseCategoryRepository->update($input, $id);

        return $this->sendResponse($renovateCourseCategory->toArray(), 'RenovateCourseCategory updated successfully');
    }

    /**
     * Remove the specified RenovateCourseCategory from storage.
     * DELETE /renovate-course-categories/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var RenovateCourseCategory $renovateCourseCategory */
        $renovateCourseCategory = $this->renovateCourseCategoryRepository->find($id);

        if (empty($renovateCourseCategory)) {
            return $this->sendError('Renovate Course Category not found');
        }

        $renovateCourseCategory->delete();

        return $this->sendSuccess('Renovate Course Category deleted successfully');
    }
}
