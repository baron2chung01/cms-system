<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateShopAPIRequest;
use App\Http\Requests\API\UpdateShopAPIRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ShopDetailResource;
use App\Http\Resources\ShopPageResource;
use App\Http\Resources\TopShopCategoryResource;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\ShopsReview;
use App\Repositories\ShopRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ShopAPIController
 */
class ShopAPIController extends AppBaseController
{
    private ShopRepository $shopRepository;
    public $deleteCount;

    public function __construct(ShopRepository $shopRepo)
    {
        $this->shopRepository = $shopRepo;
        $this->deleteCount = 0;
    }

    /**
     * Display a listing of the Shops.
     * GET|HEAD /shops
     */
    public function index(Request $request): JsonResponse
    {
        // filter: materials, price, region
        // sort: rating, date

        $filterMaterials = $request->get('materials') ?? ''; // uuid
        $filterPriceMin = $request->get('priceMin') ?? '';
        $filterPriceMax = $request->get('priceMax') ?? '';
        $filterRegion = $request->get('region') ?? ''; // uuid
        $filterCategory = $request->get('category') ?? ''; // uuid

        $sortDate = $request->get('date') ?? ''; // ASC or DESC
        $sortViews = $request->get('views') ?? ''; // ASC or DESC
        $sortRating = $request->get('rating') ?? ''; // ASC or DESC

        $shopsQuery = Shop::with(['materials_info.materials', 'regions'])->where('status', Shop::ACTIVE);

        if (!empty($filterMaterials)) {
            $shopsQuery = $shopsQuery->whereRelation('materials_info', 'materials_uuid', $filterMaterials);
        }

        if (!empty($filterRegion)) {
            $shopsQuery = $shopsQuery->whereRelation('regions', 'regions_uuid', '=', $filterRegion);
        }

        if (!empty($filterCategory)) {
            $shopsQuery = $shopsQuery->where('shop_categories_uuid', $filterCategory);
        }

        if (!empty($sortDate)) {
            if ($sortDate == "desc") {
                $shopsQuery = $shopsQuery->orderBy('updated_at', 'desc');
            } else {
                $shopsQuery = $shopsQuery->orderBy('updated_at');
            }
        }

        if (empty($sortViews) && empty($sortRating)) {
            $shopsQuery = $shopsQuery->orderByRaw('position IS NULL, position ASC')->orderBy('views', 'desc');
        }

        if (!empty($sortViews)) {
            if ($sortViews == "desc") {
                $shopsQuery = $shopsQuery->orderBy('views', 'desc');
            } else {
                $shopsQuery = $shopsQuery->orderBy('views');
            }
        }

        if (!empty($sortRating)) {
            if ($sortRating == "desc") {
                $shopsQuery = $shopsQuery->orderBy('rating', 'desc');
            } else {
                $shopsQuery = $shopsQuery->orderBy('rating');
            }
        }

        $shops = $shopsQuery->paginate(10)->appends(request()->all());

        if (!empty($filterMaterials)) {

            if (!empty($filterPriceMin) && !($shops->isEmpty())) {

                //TODO: n+1 query
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $perPage = 10;
                $total = $shops->total();

                $shopsTransformed = $shopsQuery->skip(($currentPage - 1) * $perPage)->get()->filter(
                    function ($item) use ($filterPriceMin, $filterMaterials) {
                        if (!$item->isEmpty()) {
                            $materials = $item->materials_info->where('materials_uuid', $filterMaterials);
                            $groupPrice = $materials->pluck('group_price')->first();
                            if ($groupPrice < $filterPriceMin) {
                                $this->deleteCount++;
                                return null;
                            } else {
                                return $item;
                            }
                        }
                    });

                while (count($shopsTransformed) < 10 && $this->deleteCount > 0) {
                    $skipPage = 1;
                    $shopsFill = $shopsQuery->skip($perPage * $skipPage + $this->deleteCount)->get()->filter(
                        function ($item) use ($filterPriceMin, $filterMaterials) {
                            if (!$item->isEmpty()) {
                                $materials = $item->materials_info->where('materials_uuid', $filterMaterials);
                                $groupPrice = $materials->pluck('group_price')->first();
                                if ($groupPrice < $filterPriceMin) {
                                    return null;
                                } else {
                                    return $item;
                                }
                            }
                        });
                    $fillCount = count($shopsFill);
                    foreach ($shopsFill as $item) {
                        if (count($shopsTransformed) < 10) {
                            $shopsTransformed->push($item);
                        } else {
                            break;
                        }
                    }
                    $skipPage = $skipPage + 1;
                }

                $currentItems = array_slice($shopsTransformed->toArray(), $perPage * ($currentPage - 1), $perPage);

                $shops = new LengthAwarePaginator(collect($shopsTransformed->values()), $total, $perPage, $currentPage);

                $shops->setPath(url()->current())->appends(request()->all());
            }

            if (!empty($filterPriceMax) && !($shops->isEmpty())) {

                //TODO: n+1 query
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $perPage = 10;
                $total = $shops->total();

                $shopsTransformed = $shopsQuery->skip(($currentPage - 1) * $perPage)->get()->filter(
                    function ($item) use ($filterPriceMax, $filterMaterials) {
                        if (!$item->isEmpty()) {
                            $materials = $item->materials_info->where('materials_uuid', $filterMaterials);
                            $groupPrice = $materials->pluck('group_price')->first();
                            if ($groupPrice > $filterPriceMax) {
                                $this->deleteCount++;
                                return null;
                            } else {
                                return $item;
                            }
                        }
                    });

                while (count($shopsTransformed) < 10 && $this->deleteCount > 0) {
                    $skipPage = 1;
                    $shopsFill = $shopsQuery->skip($perPage * $skipPage + $this->deleteCount)->get()->filter(
                        function ($item) use ($filterPriceMax, $filterMaterials) {
                            if (!$item->isEmpty()) {
                                $materials = $item->materials_info->where('materials_uuid', $filterMaterials);
                                $groupPrice = $materials->pluck('group_price')->first();
                                if ($groupPrice > $filterPriceMax) {
                                    return null;
                                } else {
                                    return $item;
                                }
                            }
                        });
                    $fillCount = count($shopsFill);
                    foreach ($shopsFill as $item) {
                        if (count($shopsTransformed) < 10) {
                            $shopsTransformed->push($item);
                        } else {
                            break;
                        }
                    }
                    $skipPage = $skipPage + 1;
                }

                $currentItems = array_slice($shopsTransformed->toArray(), $perPage * ($currentPage - 1), $perPage);

                $shops = new LengthAwarePaginator(collect($shopsTransformed->values()), $total, $perPage, $currentPage);

                $shops->setPath(url()->current())->appends(request()->all());
            }
        }

        if (empty($shops->items())) {
            return $this->sendError('Shop not found');
        }

        return $this->sendResponse(new ShopPageResource($shops), 'Shops retrieved successfully');
    }

    /**
     * Store a newly created Shop in storage.
     * POST /shops
     */
    public function store(CreateShopAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $shop = $this->shopRepository->create($input);

        return $this->sendResponse($shop->toArray(), 'Shop saved successfully');
    }

    /**
     * Display the specified Shop.
     * GET|HEAD /shops/{id}
     */
    public function show($uuid): JsonResponse
    {
        /** @var Shop $shop */
        $shop = Shop::with(['reviews' => function ($query) {
            $query->where('status', ShopsReview::ACTIVE);
        }])->where('shops_uuid', $uuid)->where('status', Shop::ACTIVE)->first();

        if (empty($shop)) {
            return $this->sendError('Shop not found');
        }

        $shop->update(['views' => $shop->views + 1]);

        $count = $shop->reviews()->where('status', ShopsReview::ACTIVE)->count();

        $shop->review_count = $count;

        return $this->sendResponse(new ShopDetailResource($shop), 'Shop retrieved successfully');
    }

    /**
     * Update the specified Shop in storage.
     * PUT/PATCH /shops/{id}
     */
    public function update($uuid, UpdateShopAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Shop $shop */
        $shop = $this->shopRepository->where('shops_uuid', $uuid)->first();

        if (empty($shop)) {
            return $this->sendError('Shop not found');
        }

        $shop->update($input);

        return $this->sendResponse($shop->toArray(), 'Shop updated successfully');
    }

    /**
     * Remove the specified Shop from storage.
     * DELETE /shops/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Shop $shop */
        $shop = $this->shopRepository->find($id);

        if (empty($shop)) {
            return $this->sendError('Shop not found');
        }

        $shop->delete();

        return $this->sendSuccess('Shop deleted successfully');
    }

    public function showTop()
    {
        $shopsByCat = ShopCategory::with('topShops')->get();

        return $this->sendResponse(TopShopCategoryResource::collection($shopsByCat), 'Top Shops retrieved successfully');

    }

    public function getProductDetail($productId)
    {
        $product = Product::find($productId);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        return $this->sendResponse(ProductDetailResource::make($product), 'Product saved successfully');

    }
}
