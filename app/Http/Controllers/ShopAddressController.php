<?php

namespace App\Http\Controllers;

use App\DataTables\ShopAddressDataTable;
use App\Http\Requests\CreateShopAddressRequest;
use App\Http\Requests\UpdateShopAddressRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ShopAddressRepository;
use Illuminate\Http\Request;
use Flash;

class ShopAddressController extends AppBaseController
{
    /** @var ShopAddressRepository $shopAddressRepository*/
    private $shopAddressRepository;

    public function __construct(ShopAddressRepository $shopAddressRepo)
    {
        $this->shopAddressRepository = $shopAddressRepo;
    }

    /**
     * Display a listing of the ShopAddress.
     */
    public function index(ShopAddressDataTable $shopAddressDataTable)
    {
    return $shopAddressDataTable->render('shop_addresses.index');
    }


    /**
     * Show the form for creating a new ShopAddress.
     */
    public function create()
    {
        return view('shop_addresses.create');
    }

    /**
     * Store a newly created ShopAddress in storage.
     */
    public function store(CreateShopAddressRequest $request)
    {
        $input = $request->all();

        $shopAddress = $this->shopAddressRepository->create($input);

        Flash::success('Shop Address saved successfully.');

        return redirect(route('shopAddresses.index'));
    }

    /**
     * Display the specified ShopAddress.
     */
    public function show($id)
    {
        $shopAddress = $this->shopAddressRepository->find($id);

        if (empty($shopAddress)) {
            Flash::error('Shop Address not found');

            return redirect(route('shopAddresses.index'));
        }

        return view('shop_addresses.show')->with('shopAddress', $shopAddress);
    }

    /**
     * Show the form for editing the specified ShopAddress.
     */
    public function edit($id)
    {
        $shopAddress = $this->shopAddressRepository->find($id);

        if (empty($shopAddress)) {
            Flash::error('Shop Address not found');

            return redirect(route('shopAddresses.index'));
        }

        return view('shop_addresses.edit')->with('shopAddress', $shopAddress);
    }

    /**
     * Update the specified ShopAddress in storage.
     */
    public function update($id, UpdateShopAddressRequest $request)
    {
        $shopAddress = $this->shopAddressRepository->find($id);

        if (empty($shopAddress)) {
            Flash::error('Shop Address not found');

            return redirect(route('shopAddresses.index'));
        }

        $shopAddress = $this->shopAddressRepository->update($request->all(), $id);

        Flash::success('Shop Address updated successfully.');

        return redirect(route('shopAddresses.index'));
    }

    /**
     * Remove the specified ShopAddress from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $shopAddress = $this->shopAddressRepository->find($id);

        if (empty($shopAddress)) {
            Flash::error('Shop Address not found');

            return redirect(route('shopAddresses.index'));
        }

        $this->shopAddressRepository->delete($id);

        Flash::success('Shop Address deleted successfully.');

        return redirect(route('shopAddresses.index'));
    }
}
