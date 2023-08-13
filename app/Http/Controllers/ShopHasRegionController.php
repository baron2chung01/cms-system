<?php

namespace App\Http\Controllers;

use App\DataTables\ShopHasRegionDataTable;
use App\Http\Requests\CreateShopHasRegionRequest;
use App\Http\Requests\UpdateShopHasRegionRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ShopHasRegionRepository;
use Illuminate\Http\Request;
use Flash;

class ShopHasRegionController extends AppBaseController
{
    /** @var ShopHasRegionRepository $shopHasRegionRepository*/
    private $shopHasRegionRepository;

    public function __construct(ShopHasRegionRepository $shopHasRegionRepo)
    {
        $this->shopHasRegionRepository = $shopHasRegionRepo;
    }

    /**
     * Display a listing of the ShopHasRegion.
     */
    public function index(ShopHasRegionDataTable $shopHasRegionDataTable)
    {
    return $shopHasRegionDataTable->render('shop_has_regions.index');
    }


    /**
     * Show the form for creating a new ShopHasRegion.
     */
    public function create()
    {
        return view('shop_has_regions.create');
    }

    /**
     * Store a newly created ShopHasRegion in storage.
     */
    public function store(CreateShopHasRegionRequest $request)
    {
        $input = $request->all();

        $shopHasRegion = $this->shopHasRegionRepository->create($input);

        Flash::success('Shop Has Region saved successfully.');

        return redirect(route('shopHasRegions.index'));
    }

    /**
     * Display the specified ShopHasRegion.
     */
    public function show($id)
    {
        $shopHasRegion = $this->shopHasRegionRepository->find($id);

        if (empty($shopHasRegion)) {
            Flash::error('Shop Has Region not found');

            return redirect(route('shopHasRegions.index'));
        }

        return view('shop_has_regions.show')->with('shopHasRegion', $shopHasRegion);
    }

    /**
     * Show the form for editing the specified ShopHasRegion.
     */
    public function edit($id)
    {
        $shopHasRegion = $this->shopHasRegionRepository->find($id);

        if (empty($shopHasRegion)) {
            Flash::error('Shop Has Region not found');

            return redirect(route('shopHasRegions.index'));
        }

        return view('shop_has_regions.edit')->with('shopHasRegion', $shopHasRegion);
    }

    /**
     * Update the specified ShopHasRegion in storage.
     */
    public function update($id, UpdateShopHasRegionRequest $request)
    {
        $shopHasRegion = $this->shopHasRegionRepository->find($id);

        if (empty($shopHasRegion)) {
            Flash::error('Shop Has Region not found');

            return redirect(route('shopHasRegions.index'));
        }

        $shopHasRegion = $this->shopHasRegionRepository->update($request->all(), $id);

        Flash::success('Shop Has Region updated successfully.');

        return redirect(route('shopHasRegions.index'));
    }

    /**
     * Remove the specified ShopHasRegion from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $shopHasRegion = $this->shopHasRegionRepository->find($id);

        if (empty($shopHasRegion)) {
            Flash::error('Shop Has Region not found');

            return redirect(route('shopHasRegions.index'));
        }

        $this->shopHasRegionRepository->delete($id);

        Flash::success('Shop Has Region deleted successfully.');

        return redirect(route('shopHasRegions.index'));
    }
}
