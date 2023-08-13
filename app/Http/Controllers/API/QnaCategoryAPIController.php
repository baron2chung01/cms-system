<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateQnaCategoryAPIRequest;
use App\Http\Requests\API\UpdateQnaCategoryAPIRequest;
use App\Models\QnaCategory;
use App\Repositories\QnaCategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class QnaCategoryAPIController
 */
class QnaCategoryAPIController extends AppBaseController
{
    private QnaCategoryRepository $qnaCategoryRepository;

    public function __construct(QnaCategoryRepository $qnaCategoryRepo)
    {
        $this->qnaCategoryRepository = $qnaCategoryRepo;
    }

    /**
     * Display a listing of the QnaCategories.
     * GET|HEAD /qna-categories
     */
    public function index(): JsonResponse
    {
        $qnaCategories = QnaCategory::get([
            'categories_uuid', 'name'
        ]);

        return $this->sendResponse($qnaCategories->toArray(), 'Qna Categories retrieved successfully');
    }

    /**
     * Store a newly created QnaCategory in storage.
     * POST /qna-categories
     */
    public function store(CreateQnaCategoryAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $qnaCategory = $this->qnaCategoryRepository->create($input);

        return $this->sendResponse($qnaCategory->toArray(), 'Qna Category saved successfully');
    }

    /**
     * Display the specified QnaCategory.
     * GET|HEAD /qna-categories/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var QnaCategory $qnaCategory */
        $qnaCategory = $this->qnaCategoryRepository->find($id);

        if (empty($qnaCategory)) {
            return $this->sendError('Qna Category not found');
        }

        return $this->sendResponse($qnaCategory->toArray(), 'Qna Category retrieved successfully');
    }

    /**
     * Update the specified QnaCategory in storage.
     * PUT/PATCH /qna-categories/{id}
     */
    public function update($id, UpdateQnaCategoryAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var QnaCategory $qnaCategory */
        $qnaCategory = $this->qnaCategoryRepository->find($id);

        if (empty($qnaCategory)) {
            return $this->sendError('Qna Category not found');
        }

        $qnaCategory = $this->qnaCategoryRepository->update($input, $id);

        return $this->sendResponse($qnaCategory->toArray(), 'QnaCategory updated successfully');
    }

    /**
     * Remove the specified QnaCategory from storage.
     * DELETE /qna-categories/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var QnaCategory $qnaCategory */
        $qnaCategory = $this->qnaCategoryRepository->find($id);

        if (empty($qnaCategory)) {
            return $this->sendError('Qna Category not found');
        }

        $qnaCategory->delete();

        return $this->sendSuccess('Qna Category deleted successfully');
    }
}