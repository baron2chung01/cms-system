<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Flash;

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
    public function index(Request $request)
    {
        $regions = $this->regionRepository->paginate(10);

        return view('regions.index')
            ->with('regions', $regions);
    }

    /**
     * Show the form for creating a new Region.
     */
    public function create()
    {
        return view('regions.create');
    }

    /**
     * Store a newly created Region in storage.
     */
    public function store(CreateRegionRequest $request)
    {
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
        $region = $this->regionRepository->find($id);

        if (empty($region)) {
            Flash::error('Region not found');

            return redirect(route('regions.index'));
        }

        return view('regions.edit')->with('region', $region);
    }

    /**
     * Update the specified Region in storage.
     */
    public function update($id, UpdateRegionRequest $request)
    {
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
        $region = $this->regionRepository->find($id);

        if (empty($region)) {
            Flash::error('Region not found');

            return redirect(route('regions.index'));
        }

        $this->regionRepository->delete($id);

        Flash::success('Region deleted successfully.');

        return redirect(route('regions.index'));
    }
}
