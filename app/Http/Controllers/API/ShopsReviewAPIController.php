<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateShopsReviewAPIRequest;
use App\Http\Requests\API\UpdateShopsReviewAPIRequest;
use App\Models\ShopsReview;
use App\Repositories\ShopsReviewRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class ShopsReviewAPIController
 */
class ShopsReviewAPIController extends AppBaseController
{
    private ShopsReviewRepository $shopsReviewRepository;

    public function __construct(ShopsReviewRepository $shopsReviewRepo)
    {
        $this->shopsReviewRepository = $shopsReviewRepo;
    }

    /**
     * Display a listing of the ShopsReviews.
     * GET|HEAD /shops-reviews
     */
    public function index(Request $request): JsonResponse
    {
        $shopsReviews = $this->shopsReviewRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($shopsReviews->toArray(), 'Shops Reviews retrieved successfully');
    }

    /**
     * Store a newly created ShopsReview in storage.
     * POST /shops-reviews
     */
    public function store(CreateShopsReviewAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $input['shops_reviews_uuid'] = Str::uuid();
        $input['rating'] = ($input["product_desc"] + $input["services_quality"] + $input["product_categories"] + $input["logistic_services"] + $input["geographical_location"]) / 5;
        $input['status'] = ShopsReview::DRAFT;
        $input['created_by'] = auth()->user()->clients_uuid;
        $input['updated_by'] = auth()->user()->clients_uuid;

        $shopsReview = $this->shopsReviewRepository->create($input);

        return $this->sendResponse($shopsReview->toArray(), '評論上傳成功，需待審核');
    }

    /**
     * Display the specified ShopsReview.
     * GET|HEAD /shops-reviews/{id}
     */
    public function show($uuid): JsonResponse
    {
        /** @var ShopsReview $shopsReview */
        $shopsReview = ShopsReview::where('shops_reviews_uuid', $uuid)->first();

        if (empty($shopsReview)) {
            return $this->sendError('找不到評論');
        }

        return $this->sendResponse($shopsReview->toArray(), '評論檢索成功');
    }

    /**
     * Update the specified ShopsReview in storage.
     * PUT/PATCH /shops-reviews/{id}
     */
    public function update($uuid, UpdateShopsReviewAPIRequest $request): JsonResponse
    {
        // only allowed to modify their own reviews

        $input = $request->all();

        /** @var ShopsReview $shopsReview */
        $shopsReview = ShopsReview::where('shops_reviews_uuid', $uuid)->where('created_by', auth()->user()->clients_uuid)->first();

        if (empty($shopsReview)) {
            return $this->sendError('找不到評論');
        }

        $input['rating'] = ($input["product_desc"] + $input["services_quality"] + $input["product_categories"] + $input["logistic_services"] + $input["geographical_location"]) / 5;
        $input['status'] = ShopsReview::DRAFT;
        $input['updated_by'] = auth()->user()->clients_uuid;

        $shopsReview->update($input);

        $shopsReview->refresh();

        return $this->sendResponse($shopsReview->toArray(), '評論更新成功');
    }

    /**
     * Remove the specified ShopsReview from storage.
     * DELETE /shops-reviews/{id}
     *
     * @throws \Exception
     */
    public function destroy($uuid): JsonResponse
    {
        // member only allowed to delete their own comments

        /** @var ShopsReview $shopsReview */
        $shopsReview = ShopsReview::where('shops_reviews_uuid', $uuid)->where('created_by', auth()->user()->clients_uuid)->first();

        if (empty($shopsReview)) {
            return $this->sendError('找不到評論');
        }

        $shopsReview->delete();

        return $this->sendSuccess('評論刪除成功');
    }
}
