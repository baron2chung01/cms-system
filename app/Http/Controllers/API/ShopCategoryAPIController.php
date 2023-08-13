<?php

namespace App\Http\Controllers\API;

use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ShopCategoryRepository;
use App\Http\Resources\ShopCategoryListResource;

/**
 * Class ShopCategoryAPIController
 */
class ShopCategoryAPIController extends AppBaseController
{
    private ShopCategoryRepository $shopCategoryRepository;

    public function __construct(ShopCategoryRepository $shopCategoryRepo)
    {
        $this->shopCategoryRepository = $shopCategoryRepo;
    }

    /**
     * Display a listing of the ShopCategory.
     * GET|HEAD /ShopCategory
     */
    public function index(Request $request): JsonResponse
    {
        $shopCategories = ShopCategory::get();

        return $this->sendResponse(ShopCategoryListResource::collection($shopCategories), 'Shop Category retrieved successfully');
    }
}
