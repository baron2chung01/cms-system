<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMaterialsCategoryRequest;
use App\Http\Requests\UpdateMaterialsCategoryRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MaterialsCategoryRepository;
use Illuminate\Http\Request;
use Flash;

class MaterialsCategoryController extends AppBaseController
{
    /** @var MaterialsCategoryRepository $materialsCategoryRepository*/
    private $materialsCategoryRepository;

    public function __construct(MaterialsCategoryRepository $materialsCategoryRepo)
    {
        $this->materialsCategoryRepository = $materialsCategoryRepo;
    }

    /**
     * Display a listing of the MaterialsCategory.
     */
    public function index(Request $request)
    {
        $materialsCategories = $this->materialsCategoryRepository->paginate(10);

        return view('materials_categories.index')
            ->with('materialsCategories', $materialsCategories);
    }

    /**
     * Show the form for creating a new MaterialsCategory.
     */
    public function create()
    {
        return view('materials_categories.create');
    }

    /**
     * Store a newly created MaterialsCategory in storage.
     */
    public function store(CreateMaterialsCategoryRequest $request)
    {
        $input = $request->all();

        $materialsCategory = $this->materialsCategoryRepository->create($input);

        Flash::success('Materials Category saved successfully.');

        return redirect(route('materialsCategories.index'));
    }

    /**
     * Display the specified MaterialsCategory.
     */
    public function show($id)
    {
        $materialsCategory = $this->materialsCategoryRepository->find($id);

        if (empty($materialsCategory)) {
            Flash::error('Materials Category not found');

            return redirect(route('materialsCategories.index'));
        }

        return view('materials_categories.show')->with('materialsCategory', $materialsCategory);
    }

    /**
     * Show the form for editing the specified MaterialsCategory.
     */
    public function edit($id)
    {
        $materialsCategory = $this->materialsCategoryRepository->find($id);

        if (empty($materialsCategory)) {
            Flash::error('Materials Category not found');

            return redirect(route('materialsCategories.index'));
        }

        return view('materials_categories.edit')->with('materialsCategory', $materialsCategory);
    }

    /**
     * Update the specified MaterialsCategory in storage.
     */
    public function update($id, UpdateMaterialsCategoryRequest $request)
    {
        $materialsCategory = $this->materialsCategoryRepository->find($id);

        if (empty($materialsCategory)) {
            Flash::error('Materials Category not found');

            return redirect(route('materialsCategories.index'));
        }

        $materialsCategory = $this->materialsCategoryRepository->update($request->all(), $id);

        Flash::success('Materials Category updated successfully.');

        return redirect(route('materialsCategories.index'));
    }

    /**
     * Remove the specified MaterialsCategory from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $materialsCategory = $this->materialsCategoryRepository->find($id);

        if (empty($materialsCategory)) {
            Flash::error('Materials Category not found');

            return redirect(route('materialsCategories.index'));
        }

        $this->materialsCategoryRepository->delete($id);

        Flash::success('Materials Category deleted successfully.');

        return redirect(route('materialsCategories.index'));
    }
}
