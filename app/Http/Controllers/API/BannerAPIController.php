<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateBannerAPIRequest;
use App\Http\Requests\API\UpdateBannerAPIRequest;
use App\Http\Resources\BannerListResource;
use App\Models\Banner;
use App\Models\Count;
use App\Repositories\BannerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class BannerAPIController
 */
class BannerAPIController extends AppBaseController
{
    private BannerRepository $bannerRepository;

    public function __construct(BannerRepository $bannerRepo)
    {
        $this->bannerRepository = $bannerRepo;
    }

    /**
     * Display a listing of the Banners.
     * GET|HEAD /banners
     */
    public function index(Request $request): JsonResponse
    {
        $filterModule = $request->get('module') ?? '';

        $bannersQuery = Banner::with('assets')->where('status', Banner::ACTIVE);

        if (!empty($filterModule)) {
            $bannersQuery = $bannersQuery->where('banner_module', $filterModule);
        }

        $banners = $bannersQuery->get();

        // increment views for each banner
        foreach ($banners as $banner) {
            $banner->update([
                'view_count' => $banner->view_count + 1,
            ]);
        }

        // increment views for home page
        if ($filterModule == 'home') {
            $value = Count::where('name', 'home_view')->first()->value ?? 0;
            Count::updateOrCreate([
                'name' => 'home_view',
            ], [
                'value' => $value + 1,
            ]);

        }

        if (empty($banners)) {
            return $this->sendError('Banner not found');
        }

        return $this->sendResponse(BannerListResource::collection($banners), 'Banners retrieved successfully');
    }

    /**
     * Store a newly created Banner in storage.
     * POST /banners
     */
    public function store(CreateBannerAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $banner = $this->bannerRepository->create($input);

        return $this->sendResponse($banner->toArray(), 'Banner saved successfully');
    }

    /**
     * Display the specified Banner.
     * GET|HEAD /banners/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Banner $banner */
        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            return $this->sendError('Banner not found');
        }

        return $this->sendResponse($banner->toArray(), 'Banner retrieved successfully');
    }

    /**
     * Update the specified Banner in storage.
     * PUT/PATCH /banners/{id}
     */
    public function update($id, UpdateBannerAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Banner $banner */
        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            return $this->sendError('Banner not found');
        }

        $banner = $this->bannerRepository->update($input, $id);

        return $this->sendResponse($banner->toArray(), 'Banner updated successfully');
    }

    /**
     * Remove the specified Banner from storage.
     * DELETE /banners/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Banner $banner */
        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            return $this->sendError('Banner not found');
        }

        $banner->delete();

        return $this->sendSuccess('Banner deleted successfully');
    }
}
