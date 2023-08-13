<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ShopRepository;
use Illuminate\Http\Request;
use Flash;

class ShopController extends AppBaseController
{
    /** @var ShopRepository $shopRepository*/
    private $shopRepository;

    public function __construct(ShopRepository $shopRepo)
    {
        $this->shopRepository = $shopRepo;
    }

    /**
     * Display a listing of the Shop.
     */
    public function index(Request $request)
    {
        $shops = $this->shopRepository->paginate(10);

        return view('shops.index')
            ->with('shops', $shops);
    }

    /**
     * Show the form for creating a new Shop.
     */
    public function create()
    {
        return view('shops.create');
    }

    /**
     * Store a newly created Shop in storage.
     */
    public function store(CreateShopRequest $request)
    {
        $input = $request->all();

        $shop = $this->shopRepository->create($input);

        Flash::success('Shop saved successfully.');

        return redirect(route('shops.index'));
    }

    /**
     * Display the specified Shop.
     */
    public function show($id)
    {
        $shop = $this->shopRepository->find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('shops.index'));
        }

        return view('shops.show')->with('shop', $shop);
    }

    /**
     * Show the form for editing the specified Shop.
     */
    public function edit($id)
    {
        $shop = $this->shopRepository->find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('shops.index'));
        }

        return view('shops.edit')->with('shop', $shop);
    }

    /**
     * Update the specified Shop in storage.
     */
    public function update($id, UpdateShopRequest $request)
    {
        $shop = $this->shopRepository->find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('shops.index'));
        }

        $shop = $this->shopRepository->update($request->all(), $id);

        Flash::success('Shop updated successfully.');

        return redirect(route('shops.index'));
    }

    /**
     * Remove the specified Shop from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $shop = $this->shopRepository->find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('shops.index'));
        }

        $this->shopRepository->delete($id);

        Flash::success('Shop deleted successfully.');

        return redirect(route('shops.index'));
    }
}
