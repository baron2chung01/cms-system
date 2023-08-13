<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAssetsRequest;
use App\Http\Requests\UpdateAssetsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\AssetsRepository;
use Illuminate\Http\Request;
use Flash;

class AssetsController extends AppBaseController
{
    /** @var AssetsRepository $assetsRepository*/
    private $assetsRepository;

    public function __construct(AssetsRepository $assetsRepo)
    {
        $this->assetsRepository = $assetsRepo;
    }

    /**
     * Display a listing of the Assets.
     */
    public function index(Request $request)
    {
        $assets = $this->assetsRepository->paginate(10);

        return view('assets.index')
            ->with('assets', $assets);
    }

    /**
     * Show the form for creating a new Assets.
     */
    public function create()
    {
        return view('assets.create');
    }

    /**
     * Store a newly created Assets in storage.
     */
    public function store(CreateAssetsRequest $request)
    {
        $input = $request->all();

        $assets = $this->assetsRepository->create($input);

        Flash::success('Assets saved successfully.');

        return redirect(route('assets.index'));
    }

    /**
     * Display the specified Assets.
     */
    public function show($id)
    {
        $assets = $this->assetsRepository->find($id);

        if (empty($assets)) {
            Flash::error('Assets not found');

            return redirect(route('assets.index'));
        }

        return view('assets.show')->with('assets', $assets);
    }

    /**
     * Show the form for editing the specified Assets.
     */
    public function edit($id)
    {
        $assets = $this->assetsRepository->find($id);

        if (empty($assets)) {
            Flash::error('Assets not found');

            return redirect(route('assets.index'));
        }

        return view('assets.edit')->with('assets', $assets);
    }

    /**
     * Update the specified Assets in storage.
     */
    public function update($id, UpdateAssetsRequest $request)
    {
        $assets = $this->assetsRepository->find($id);

        if (empty($assets)) {
            Flash::error('Assets not found');

            return redirect(route('assets.index'));
        }

        $assets = $this->assetsRepository->update($request->all(), $id);

        Flash::success('Assets updated successfully.');

        return redirect(route('assets.index'));
    }

    /**
     * Remove the specified Assets from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $assets = $this->assetsRepository->find($id);

        if (empty($assets)) {
            Flash::error('Assets not found');

            return redirect(route('assets.index'));
        }

        $this->assetsRepository->delete($id);

        Flash::success('Assets deleted successfully.');

        return redirect(route('assets.index'));
    }
}
