<?php

namespace App\Http\Controllers;

use App\DataTables\RegionDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Repositories\RegionRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegionController extends AppBaseController
{
    /** @var RegionRepository $regionRepository*/
    private $regionRepository;

    public function __construct(RegionRepository $regionRepo)
    {
        $this->regionRepository = $regionRepo;
    }

    /**
     * Display a listing of the Region.
     */
    public function index(RegionDataTable $regionDataTable)
    {
        $this->authorize('regions_access');

        return $regionDataTable->render('regions.index');
    }

    /**
     * Show the form for creating a new Region.
     */
    public function create()
    {
        $this->authorize('regions_create');

        $user = Auth::user();
        return view('regions.create', compact('user'));
    }

    /**
     * Store a newly created Region in storage.
     */
    public function store(CreateRegionRequest $request)
    {
        $this->authorize('regions_create');

        $input = $request->all();

        $region = $this->regionRepository->create($input);

        Flash::success('Region saved successfully.');

        return redirect(route('regions.index'));
    }

    /**
     * Display the specified Region.
     */
    public function show($id)
    {
        $this->authorize('regions_show');

        $region = $this->regionRepository->find($id);

        if (empty($region)) {
            Flash::error('Region not found');

            return redirect(route('regions.index'));
        }

        return view('regions.show')->with('region', $region);
    }

    /**
     * Show the form for editing the specified Region.
     */
    public function edit($id)
    {
        $this->authorize('regions_edit');

        $region = $this->regionRepository->find($id);

        if (empty($region)) {
            Flash::error('Region not found');

            return redirect(route('regions.index'));
        }

        $user = Auth::user();

        return view('regions.edit', compact('region', 'user'));
    }

    /**
     * Update the specified Region in storage.
     */
    public function update($id, UpdateRegionRequest $request)
    {
        $this->authorize('regions_edit');

        $region = $this->regionRepository->find($id);

        if (empty($region)) {
            Flash::error('Region not found');

            return redirect(route('regions.index'));
        }

        $region = $this->regionRepository->update($request->all(), $id);

        Flash::success('Region updated successfully.');

        return redirect(route('regions.index'));
    }

    /**
     * Remove the specified Region from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('regions_delete');

        $region = $this->regionRepository->find($id);

        if (empty($region)) {
            Flash::error('Region not found');

            return redirect(route('regions.index'));
        }

        \DB::transaction(function () use ($region, $id) {
            $this->regionRepository->delete($id);
        });

        Flash::success('Region deleted successfully.');

        return redirect(route('regions.index'));
    }
}
