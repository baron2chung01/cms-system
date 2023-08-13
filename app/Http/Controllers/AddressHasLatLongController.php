<?php

namespace App\Http\Controllers;

use App\DataTables\AddressHasLatLongDataTable;
use App\Http\Requests\CreateAddressHasLatLongRequest;
use App\Http\Requests\UpdateAddressHasLatLongRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\AddressHasLatLongRepository;
use Illuminate\Http\Request;
use Flash;

class AddressHasLatLongController extends AppBaseController
{
    /** @var AddressHasLatLongRepository $addressHasLatLongRepository*/
    private $addressHasLatLongRepository;

    public function __construct(AddressHasLatLongRepository $addressHasLatLongRepo)
    {
        $this->addressHasLatLongRepository = $addressHasLatLongRepo;
    }

    /**
     * Display a listing of the AddressHasLatLong.
     */
    public function index(AddressHasLatLongDataTable $addressHasLatLongDataTable)
    {
    return $addressHasLatLongDataTable->render('address_has_lat_longs.index');
    }


    /**
     * Show the form for creating a new AddressHasLatLong.
     */
    public function create()
    {
        return view('address_has_lat_longs.create');
    }

    /**
     * Store a newly created AddressHasLatLong in storage.
     */
    public function store(CreateAddressHasLatLongRequest $request)
    {
        $input = $request->all();

        $addressHasLatLong = $this->addressHasLatLongRepository->create($input);

        Flash::success('Address Has Lat Long saved successfully.');

        return redirect(route('addressHasLatLongs.index'));
    }

    /**
     * Display the specified AddressHasLatLong.
     */
    public function show($id)
    {
        $addressHasLatLong = $this->addressHasLatLongRepository->find($id);

        if (empty($addressHasLatLong)) {
            Flash::error('Address Has Lat Long not found');

            return redirect(route('addressHasLatLongs.index'));
        }

        return view('address_has_lat_longs.show')->with('addressHasLatLong', $addressHasLatLong);
    }

    /**
     * Show the form for editing the specified AddressHasLatLong.
     */
    public function edit($id)
    {
        $addressHasLatLong = $this->addressHasLatLongRepository->find($id);

        if (empty($addressHasLatLong)) {
            Flash::error('Address Has Lat Long not found');

            return redirect(route('addressHasLatLongs.index'));
        }

        return view('address_has_lat_longs.edit')->with('addressHasLatLong', $addressHasLatLong);
    }

    /**
     * Update the specified AddressHasLatLong in storage.
     */
    public function update($id, UpdateAddressHasLatLongRequest $request)
    {
        $addressHasLatLong = $this->addressHasLatLongRepository->find($id);

        if (empty($addressHasLatLong)) {
            Flash::error('Address Has Lat Long not found');

            return redirect(route('addressHasLatLongs.index'));
        }

        $addressHasLatLong = $this->addressHasLatLongRepository->update($request->all(), $id);

        Flash::success('Address Has Lat Long updated successfully.');

        return redirect(route('addressHasLatLongs.index'));
    }

    /**
     * Remove the specified AddressHasLatLong from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $addressHasLatLong = $this->addressHasLatLongRepository->find($id);

        if (empty($addressHasLatLong)) {
            Flash::error('Address Has Lat Long not found');

            return redirect(route('addressHasLatLongs.index'));
        }

        $this->addressHasLatLongRepository->delete($id);

        Flash::success('Address Has Lat Long deleted successfully.');

        return redirect(route('addressHasLatLongs.index'));
    }
}
