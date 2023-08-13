<?php

namespace App\Http\Controllers;

use App\DataTables\MaterialsCategoryDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateMaterialsCategoryRequest;
use App\Http\Requests\UpdateMaterialsCategoryRequest;
use App\Models\MaterialsCategory;
use App\Repositories\MaterialsCategoryRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index(MaterialsCategoryDataTable $materialsCategoryDataTable)
    {
        return $materialsCategoryDataTable->render('materials_categories.index');
    }

    /**
     * Show the form for creating a new MaterialsCategory.
     */
    public function create()
    {
        $parents = MaterialsCategory::pluck('name', 'materials_categories_uuid');
        $status  = MaterialsCategory::STATUS;
        $user    = Auth::user();
        return view('materials_categories.create', compact('parents', 'status', 'user'));
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
        $status   = MaterialsCategory::STATUS;
        $user     = Auth::user();
        $parents  = MaterialsCategory::pluck('name', 'materials_categories_uuid');
        $selfUuid = MaterialsCategory::where('id', $id)->pluck('name', 'materials_categories_uuid');
        $parents  = $parents->diff($selfUuid);

        return view('materials_categories.edit', compact('materialsCategory', 'user', 'status', 'parents'));
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
        $materialsCategory = $this->materialsCategoryRepository->with('materials')->find($id);

        if (empty($materialsCategory)) {
            Flash::error('Materials Category not found');

            return redirect(route('materialsCategories.index'));
        }

        \DB::transaction(function () use ($materialsCategory, $id) {
            $materialsCategory->update([
                'status'     => MaterialsCategory::DELETE,
                'deleted_by' => Auth::user()->users_uuid,
            ]);

            $materialsCategory->materials()->delete();

            // parent category: remove all child
            $childCategories = MaterialsCategory::where('parents_uuid', $materialsCategory->materials_categories_uuid)->get();
            if (isset($childCategories)) {
                foreach ($childCategories as $child) {
                    $child->materials()->delete();
                    $child->delete();
                }
            }

            $this->materialsCategoryRepository->delete($id);
        });

        Flash::success('Materials Category deleted successfully.');

        return redirect(route('materialsCategories.index'));
    }
}