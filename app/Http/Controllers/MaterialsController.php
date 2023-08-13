<?php

namespace App\Http\Controllers;

use Flash;
use App\Models\Assets;
use App\Models\Materials;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MaterialsShop;
use App\Models\MaterialsCategory;
use Illuminate\Support\Facades\Auth;
use App\DataTables\MaterialsDataTable;
use App\Repositories\MaterialsRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateMaterialsRequest;
use App\Http\Requests\UpdateMaterialsRequest;

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
    public function index(MaterialsDataTable $materialsDataTable)
    {
        return $materialsDataTable->render('materials.index');
    }

    /**
     * Show the form for creating a new Materials.
     */
    public function create()
    {
        $categories = MaterialsCategory::pluck('name', 'materials_categories_uuid');
        $status     = Materials::STATUS;
        $user       = Auth::user();

        return view('materials.create', compact('categories', 'status', 'user'));
    }

    /**
     * Store a newly created Materials in storage.
     */
    public function store(CreateMaterialsRequest $request)
    {
        $input = $request->all();

        $materials = $this->materialsRepository->create($input);

        // handle image asset
        $images = $request->file('file-input');

        if (isset($images)) {
            foreach ($images as $image) {
                $imageName = date("Ymdhis") . $image->getClientOriginalName();
                $image->storeAs('public/', $imageName);
                Assets::create([
                    'assets_uuid' => Str::uuid(),
                    'module_uuid' => $materials->materials_uuid,
                    'resource_path' => "/storage/{$imageName}",
                    'file_name' => $imageName,
                    'type' => $image->getClientMimeType(),
                    'file_size' => $image->getSize(),
                    'status' => Assets::ACTIVE,
                ]);
            }
        }

        Flash::success('Materials saved successfully.');

        return redirect(route('materials.index'));
    }

    /**
     * Display the specified Materials.
     */
    public function show($id)
    {
        $materials = $this->materialsRepository->with(['category', 'shops_info.shop'])->find($id);

        if (empty($materials)) {
            Flash::error('Materials not found');

            return redirect(route('materials.index'));
        }

        $shops_info = $materials->shops_info;

        $images = Assets::where('module_uuid', $materials->materials_uuid)->get();


        return view('materials.show', compact('materials', 'shops_info', 'images'));
    }

    /**
     * Show the form for editing the specified Materials.
     */
    public function edit($id)
    {
        $materials  = $this->materialsRepository->find($id);
        $categories = MaterialsCategory::pluck('name', 'materials_categories_uuid');
        $status     = Materials::STATUS;
        $user       = Auth::user();

        if (empty($materials)) {
            Flash::error('Materials not found');

            return redirect(route('materials.index'));
        }

        $images = Assets::where('module_uuid', $materials->materials_uuid)->get();


        return view('materials.edit', compact('categories', 'materials', 'status', 'user', 'images'));
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

        \DB::transaction(function () use ($materials, $id) {
            $this->materialsRepository->update([
                'status'     => Materials::DELETE,
                'deleted_by' => Auth::user()->users_uuid,
            ], $id);
            MaterialsShop::where('materials_uuid', $materials->materials_uuid)->delete();

            $this->materialsRepository->delete($id);
        });

        Flash::success('Materials deleted successfully.');

        return redirect(route('materials.index'));
    }
}