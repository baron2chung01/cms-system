<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\RenovateCourse;
use Illuminate\Http\JsonResponse;
use App\Models\RenovateCourseCategory;
use App\Http\Controllers\AppBaseController;
use App\Repositories\RenovateCourseRepository;
use App\Http\Resources\RenovateCoursePageResource;
use App\Http\Resources\RenovateCourseDetailResource;
use App\Http\Requests\API\CreateRenovateCourseAPIRequest;
use App\Http\Requests\API\UpdateRenovateCourseAPIRequest;

/**
 * Class RenovateCourseAPIController
 */
class RenovateCourseAPIController extends AppBaseController
{
    private RenovateCourseRepository $renovateCourseRepository;

    public function __construct(RenovateCourseRepository $renovateCourseRepo)
    {
        $this->renovateCourseRepository = $renovateCourseRepo;
    }

    /**
     * Display a listing of the RenovateCourses.
     * GET|HEAD /renovate-courses
     */
    public function index(): JsonResponse
    {
        $courseQuery = RenovateCourseCategory::with('courses.assets')->where('status', RenovateCourseCategory::ACTIVE);

        $renovateCourses = $courseQuery->get();

        return $this->sendResponse(RenovateCoursePageResource::collection($renovateCourses), 'Renovate Courses retrieved successfully');
    }

    /**
     * Store a newly created RenovateCourse in storage.
     * POST /renovate-courses
     */
    public function store(CreateRenovateCourseAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $renovateCourse = $this->renovateCourseRepository->create($input);

        return $this->sendResponse($renovateCourse->toArray(), 'Renovate Course saved successfully');
    }

    /**
     * Display the specified RenovateCourse.
     * GET|HEAD /renovate-courses/{id}
     */
    public function show($uuid): JsonResponse
    {
        /** @var RenovateCourse $renovateCourse */
        $renovateCourse = $this->renovateCourseRepository->where('renovate_course_uuid', $uuid)->first();

        if (empty($renovateCourse)) {
            return $this->sendError('Renovate Course not found');
        }

        // dd($renovateCourse);

        return $this->sendResponse(new RenovateCourseDetailResource($renovateCourse), 'renovateCourse retrieved successfully');
    }

    /**
     * Update the specified RenovateCourse in storage.
     * PUT/PATCH /renovate-courses/{id}
     */
    public function update($id, UpdateRenovateCourseAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var RenovateCourse $renovateCourse */
        $renovateCourse = $this->renovateCourseRepository->find($id);

        if (empty($renovateCourse)) {
            return $this->sendError('Renovate Course not found');
        }

        $renovateCourse = $this->renovateCourseRepository->update($input, $id);

        return $this->sendResponse($renovateCourse->toArray(), 'RenovateCourse updated successfully');
    }

    /**
     * Remove the specified RenovateCourse from storage.
     * DELETE /renovate-courses/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var RenovateCourse $renovateCourse */
        $renovateCourse = $this->renovateCourseRepository->find($id);

        if (empty($renovateCourse)) {
            return $this->sendError('Renovate Course not found');
        }

        $renovateCourse->delete();

        return $this->sendSuccess('Renovate Course deleted successfully');
    }
}