<?php

namespace App\Http\Controllers\API;

use App\Models\Qna;
use App\Models\QnaCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\QnaRepository;
use App\Http\Resources\QnaListResource;
use App\Http\Resources\QnaPageResource;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateQnaAPIRequest;
use App\Http\Requests\API\UpdateQnaAPIRequest;

/**
 * Class QnaAPIController
 */
class QnaAPIController extends AppBaseController
{
    private QnaRepository $qnaRepository;

    public function __construct(QnaRepository $qnaRepo)
    {
        $this->qnaRepository = $qnaRepo;
    }

    /**
     * Display a listing of the Qnas.
     * GET|HEAD /qnas
     */
    public function index(Request $request): JsonResponse
    {
        $qnas = QnaCategory::with(['qnas' => function ($query) {
            $query->where('status', Qna::ACTIVE);
        }])->where('status', QnaCategory::ACTIVE)->get();

        if (empty($qnas)) {
            return $this->sendError('Qna not found');
        }

        return $this->sendResponse(QnaPageResource::collection($qnas), 'Qnas retrieved successfully');

    }

    /**
     * Store a newly created Qna in storage.
     * POST /qnas
     */
    public function store(CreateQnaAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $qna = $this->qnaRepository->create($input);

        return $this->sendResponse($qna->toArray(), 'Qna saved successfully');
    }

    /**
     * Display the specified Qna.
     * GET|HEAD /qnas/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Qna $qna */
        $qna = $this->qnaRepository->find($id);

        if (empty($qna)) {
            return $this->sendError('Qna not found');
        }

        return $this->sendResponse($qna->toArray(), 'Qna retrieved successfully');
    }

    /**
     * Update the specified Qna in storage.
     * PUT/PATCH /qnas/{id}
     */
    public function update($id, UpdateQnaAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Qna $qna */
        $qna = $this->qnaRepository->find($id);

        if (empty($qna)) {
            return $this->sendError('Qna not found');
        }

        $qna = $this->qnaRepository->update($input, $id);

        return $this->sendResponse($qna->toArray(), 'Qna updated successfully');
    }

    /**
     * Remove the specified Qna from storage.
     * DELETE /qnas/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Qna $qna */
        $qna = $this->qnaRepository->find($id);

        if (empty($qna)) {
            return $this->sendError('Qna not found');
        }

        $qna->delete();

        return $this->sendSuccess('Qna deleted successfully');
    }
}
