<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateSpecialPromotionAPIRequest;
use App\Http\Requests\API\UpdateSpecialPromotionAPIRequest;
use App\Http\Resources\AssetsResource;
use App\Http\Resources\SpecialPromotionDetailResource;
use App\Http\Resources\SpecialPromotionResource;
use App\Models\Assets;
use App\Models\Shop;
use App\Models\SpecialPromotion;
use App\Repositories\SpecialPromotionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Log\NullLogger;

/**
 * Class SpecialPromotionAPIController
 */
class SpecialPromotionAPIController extends AppBaseController
{
    private SpecialPromotionRepository $specialPromotionRepository;

    public function __construct(SpecialPromotionRepository $specialPromotionRepo)
    {
        $this->specialPromotionRepository = $specialPromotionRepo;
    }

    /**
     * Display a listing of the SpecialPromotions.
     * GET|HEAD /special-promotions
     */
    public function index(Request $request): JsonResponse
    {
        $specialPromotions = SpecialPromotion::where('status', SpecialPromotion::ACTIVE)->get();

        return $this->sendResponse(SpecialPromotionResource::collection($specialPromotions), 'Special Promotions retrieved successfully');
    }

    /**
     * Store a newly created SpecialPromotion in storage.
     * POST /special-promotions
     */
    public function store(CreateSpecialPromotionAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $specialPromotion = $this->specialPromotionRepository->create($input);

        return $this->sendResponse($specialPromotion->toArray(), 'Special Promotion saved successfully');
    }

    /**
     * Display the specified SpecialPromotion.
     * GET|HEAD /special-promotions/{id}
     */
    public function show($uuid): JsonResponse
    {
        /** @var SpecialPromotion $specialPromotion */
        $specialPromotion = SpecialPromotion::where('special_promotion_uuid', $uuid)->first();
        $shop = $specialPromotion->shops->first();

        if (empty($specialPromotion)) {
            return $this->sendError('Special Promotion not found');
        }
        if (empty(auth('api')->user())) {
            $receiptsResource = null;
        } else {
            $receipts = Assets::where('module_uuid', $uuid)->where('second_module_uuid', $shop->shops_uuid)
                ->where('created_by', auth('api')->user()->members_uuid)->get();

            $receiptsResource = count($receipts) ? AssetsResource::collection($receipts) : null;
        }

        $specialPromotion->receipts = $receiptsResource;

        return $this->sendResponse(SpecialPromotionDetailResource::make($specialPromotion), 'Special Promotion retrieved successfully');

    }

    /**
     * Display the specified SpecialPromotion by shop.
     * GET|HEAD /special-promotions/{id}
     */
    public function showByShop($shopUuid, $promoUuid): JsonResponse
    {
        /** @var SpecialPromotion $specialPromotion */
        $specialPromotion = SpecialPromotion::where('special_promotion_uuid', $promoUuid)->first();
        $shop = Shop::where('shops_uuid', $shopUuid)->first();

        if (empty($specialPromotion)) {
            return $this->sendError('Special Promotion not found');
        }
        if (!in_array($shopUuid, $specialPromotion->shops->pluck('shops_uuid')->toArray())) {
            return $this->sendError('Special Promotion not found.');
        }
        if (empty(auth('api')->user())) {
            $receiptsResource = null;
        } else {
            $receipts = Assets::where('module_uuid', $promoUuid)->where('second_module_uuid', $shopUuid)
                ->where('created_by', auth('api')->user()->members_uuid)->get();

            $receiptsResource = count($receipts) ? AssetsResource::collection($receipts) : null;
        }

        $specialPromotion->receipts = $receiptsResource;

        return $this->sendResponse(SpecialPromotionDetailResource::make($specialPromotion), 'Special Promotion retrieved successfully');
    }

    /**
     * Update the specified SpecialPromotion in storage.
     * PUT/PATCH /special-promotions/{id}
     */
    public function update($id, UpdateSpecialPromotionAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var SpecialPromotion $specialPromotion */
        $specialPromotion = $this->specialPromotionRepository->find($id);

        if (empty($specialPromotion)) {
            return $this->sendError('Special Promotion not found');
        }

        $specialPromotion = $this->specialPromotionRepository->update($input, $id);

        return $this->sendResponse($specialPromotion->toArray(), 'SpecialPromotion updated successfully');
    }

    /**
     * Remove the specified SpecialPromotion from storage.
     * DELETE /special-promotions/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var SpecialPromotion $specialPromotion */
        $specialPromotion = $this->specialPromotionRepository->find($id);

        if (empty($specialPromotion)) {
            return $this->sendError('Special Promotion not found');
        }

        $specialPromotion->delete();

        return $this->sendSuccess('Special Promotion deleted successfully');
    }

    public function getReceipt($uuid)
    {
        $specialPromotion = SpecialPromotion::where('special_promotion_uuid', $uuid)->first();

        $memberUuid = auth('api')->user()->members_uuid;

        if (empty($specialPromotion)) {
            return $this->sendError('找不到優惠');
        }

        $receipt = Assets::where('module_uuid', $uuid)->where('created_by', $memberUuid)->where('status', '<=', Assets::APPROVED)->first();

        if (empty($receipt)) {
            return $this->sendError('找不到收據');
        }

        return $this->sendSuccess(AssetsResource::make($receipt), '成功找到收據');

    }
}
