<?php

namespace App\Http\Controllers;

use App\DataTables\MaterialsShopDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateMaterialsShopRequest;
use App\Http\Requests\UpdateMaterialsShopRequest;
use App\Models\Materials;
use App\Models\MaterialsShop;
use App\Models\Shop;
use App\Repositories\MaterialsShopRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MaterialsShopController extends AppBaseController
{
    /** @var MaterialsShopRepository $materialsShopRepository*/
    private $materialsShopRepository;

    public function __construct(MaterialsShopRepository $materialsShopRepo)
    {
        $this->materialsShopRepository = $materialsShopRepo;
    }

    /**
     * Display a listing of the MaterialsShop.
     */
    public function index(MaterialsShopDataTable $materialsShopDataTable)
    {
        $shops     = Shop::pluck('name');
        $materials = Materials::pluck('name');
        return $materialsShopDataTable->render('materials_shops.index', compact('shops', 'materials'));
    }

    /**
     * Show the form for creating a new MaterialsShop.
     */
    public function create()
    {
        $user      = Auth::user();
        $materials = Materials::pluck('name', 'materials_uuid');
        $shops     = Shop::pluck('name', 'shops_uuid');
        $status    = MaterialsShop::STATUS;

        return view('materials_shops.create', compact('user', 'materials', 'shops', 'status'));
    }

    /**
     * Store a newly created MaterialsShop in storage.
     */
    public function store(CreateMaterialsShopRequest $request)
    {
        $input = $request->all();

        request()->validate([

            'shops_uuid' => [

                'required',

                Rule::unique('materials_shops')->where(function ($query) use ($request) {

                    return $query
                        ->where('materials_uuid', $request->materials_uuid)
                        ->where('shops_uuid', $request->shops_uuid);
                }),
            ],
        ],
            [
                'shops_uuid.unique' => __('Duplicate record already exists for this Shop and Materials.'),
            ]);

        $materialsShop = $this->materialsShopRepository->create($input);

        Flash::success('Materials Shop saved successfully.');

        return redirect(route('materialsShops.index'));
    }

    /**
     * Display the specified MaterialsShop.
     */
    public function show($id)
    {
        $materialsShop = $this->materialsShopRepository->find($id);

        if (empty($materialsShop)) {
            Flash::error('Materials Shop not found');

            return redirect(route('materialsShops.index'));
        }

        return view('materials_shops.show')->with('materialsShop', $materialsShop);
    }

    /**
     * Show the form for editing the specified MaterialsShop.
     */
    public function edit($id)
    {
        $materialsShop = $this->materialsShopRepository->find($id);

        if (empty($materialsShop)) {
            Flash::error('Materials Shop not found');

            return redirect(route('materialsShops.index'));
        }

        $user      = Auth::user();
        $materials = Materials::pluck('name', 'materials_uuid');
        $shops     = Shop::pluck('name', 'shops_uuid');
        $status    = MaterialsShop::STATUS;

        return view('materials_shops.edit', compact('materialsShop', 'user', 'materials', 'shops', 'status'));
    }

    /**
     * Update the specified MaterialsShop in storage.
     */
    public function update($id, UpdateMaterialsShopRequest $request)
    {
        $materialsShop = $this->materialsShopRepository->find($id);

        request()->validate([

            'shops_uuid' => [

                'required',

                Rule::unique('materials_shops')->where(function ($query) use ($request) {

                    return $query
                        ->where('materials_uuid', $request->materials_uuid)
                        ->where('shops_uuid', $request->shops_uuid);
                }),
            ],
        ],
            [
                'shops_uuid.unique' => __('Duplicate record already exists for this Shop and Materials.'),
            ]);

        if (empty($materialsShop)) {
            Flash::error('Materials Shop not found');

            return redirect(route('materialsShops.index'));
        }

        $materialsShop = $this->materialsShopRepository->update($request->all(), $id);

        Flash::success('Materials Shop updated successfully.');

        return redirect(route('materialsShops.index'));
    }

    /**
     * Remove the specified MaterialsShop from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $materialsShop = $this->materialsShopRepository->find($id);

        if (empty($materialsShop)) {
            Flash::error('Materials Shop not found');

            return redirect(route('materialsShops.index'));
        }

        $this->materialsShopRepository->delete($id);

        Flash::success('Materials Shop deleted successfully.');

        return redirect(route('materialsShops.index'));
    }
}