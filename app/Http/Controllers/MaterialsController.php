<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMaterialsRequest;
use App\Http\Requests\UpdateMaterialsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MaterialsRepository;
use Illuminate\Http\Request;
use Flash;

class MaterialsController extends AppBaseController
{
    /** @var MaterialsRepository $materialsRepository*/
    private $materialsRepository;

    public function __construct(MaterialsRepository $materialsRepo)
    {
        $this->materialsRepository = $materialsRepo;
    }

    /**
     * Display a listing of the Materials.
     */
    public function index(Request $request)
    {
        $materials = $this->materialsRepository->paginate(10);

        return view('materials.index')
            ->with('materials', $materials);
    }

    /**
     * Show the form for creating a new Materials.
     */
    public function create()
    {
        return view('materials.create');
    }

    /**
     * Store a newly created Materials in storage.
     */
    public function store(CreateMaterialsRequest $request)
    {
        $input = $request->all();

        $materials = $this->materialsRepository->create($input);

        Flash::success('Materials saved successfully.');

        return redirect(route('materials.index'));
    }

    /**
     * Display the specified Materials.
     */
    public function show($id)
    {
        $materials = $this->materialsRepository->find($id);

        if (empty($materials)) {
            Flash::error('Materials not found');

            return redirect(route('materials.index'));
        }

        return view('materials.show')->with('materials', $materials);
    }

    /**
     * Show the form for editing the specified Materials.
     */
    public function edit($id)
    {
        $materials = $this->materialsRepository->find($id);

        if (empty($materials)) {
            Flash::error('Materials not found');

            return redirect(route('materials.index'));
        }

        return view('materials.edit')->with('materials', $materials);
    }

    /**
     * Update the specified Materials in storage.
     */
    public function update($id, UpdateMaterialsRequest $request)
    {
        $materials = $this->materialsRepository->find($id);

        if (empty($materials)) {
            Flash::error('Materials not found');

            return redirect(route('materials.index'));
        }

        $materials = $this->materialsRepository->update($request->all(), $id);

        Flash::success('Materials updated successfully.');

        return redirect(route('materials.index'));
    }

    /**
     * Remove the specified Materials from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $materials = $this->materialsRepository->find($id);

        if (empty($materials)) {
            Flash::error('Materials not found');

            return redirect(route('materials.index'));
        }

        $this->materialsRepository->delete($id);

        Flash::success('Materials deleted successfully.');

        return redirect(route('materials.index'));
    }
}
