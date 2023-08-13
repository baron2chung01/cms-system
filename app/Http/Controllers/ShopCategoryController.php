<?php

namespace App\Http\Controllers;

use App\DataTables\ShopCategoryDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateShopCategoryRequest;
use App\Http\Requests\UpdateShopCategoryRequest;
use App\Models\ShopCategory;
use App\Repositories\ShopCategoryRepository;
use Flash;
use Illuminate\Http\Request;

class ShopCategoryController extends AppBaseController
{
    /** @var ShopCategoryRepository $shopCategoryRepository*/
    private $shopCategoryRepository;

    public function __construct(ShopCategoryRepository $shopCategoryRepo)
    {
        $this->shopCategoryRepository = $shopCategoryRepo;
    }

    /**
     * Display a listing of the ShopCategory.
     */
    public function index(ShopCategoryDataTable $shopCategoryDataTable)
    {
        $this->authorize('shop_categories_access');

        return $shopCategoryDataTable->render('shop_categories.index');
    }

    /**
     * Show the form for creating a new ShopCategory.
     */
    public function create()
    {
        $this->authorize('shop_categories_create');

        $status  = ShopCategory::DISP_STATUS;
        $parents = ShopCategory::pluck('name', 'shop_categories_uuid');

        return view('shop_categories.create', compact('status', 'parents'));
    }

    /**
     * Store a newly created ShopCategory in storage.
     */
    public function store(CreateShopCategoryRequest $request)
    {
        $this->authorize('shop_categories_create');

        $input = $request->all();

        $shopCategory = $this->shopCategoryRepository->create($input);

        Flash::success('Shop Category saved successfully.');

        return redirect(route('shopCategories.index'));
    }

    /**
     * Display the specified ShopCategory.
     */
    public function show($id)
    {
        $this->authorize('shop_categories_show');

        $shopCategory = $this->shopCategoryRepository->find($id);

        if (empty($shopCategory)) {
            Flash::error('Shop Category not found');

            return redirect(route('shopCategories.index'));
        }

        return view('shop_categories.show')->with('shopCategory', $shopCategory);
    }

    /**
     * Show the form for editing the specified ShopCategory.
     */
    public function edit($id)
    {
        $this->authorize('shop_categories_edit');

        $shopCategory = $this->shopCategoryRepository->find($id);

        if (empty($shopCategory)) {
            Flash::error('Shop Category not found');

            return redirect(route('shopCategories.index'));
        }

        $status = ShopCategory::DISP_STATUS;

        $parents = ShopCategory::pluck('name', 'shop_categories_uuid');

        $selfUuid = ShopCategory::where('id', $id)->pluck('name', 'shop_categories_uuid');

        $parents = $parents->diff($selfUuid);

        return view('shop_categories.edit', compact('shopCategory', 'status', 'parents'));
    }

    /**
     * Update the specified ShopCategory in storage.
     */
    public function update($id, UpdateShopCategoryRequest $request)
    {
        $this->authorize('shop_categories_edit');

        $shopCategory = $this->shopCategoryRepository->find($id);

        if (empty($shopCategory)) {
            Flash::error('Shop Category not found');

            return redirect(route('shopCategories.index'));
        }

        $shopCategory = $this->shopCategoryRepository->update($request->all(), $id);

        Flash::success('Shop Category updated successfully.');

        return redirect(route('shopCategories.index'));
    }

    /**
     * Remove the specified ShopCategory from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('shop_categories_delete');

        $shopCategory = $this->shopCategoryRepository->find($id);

        if (empty($shopCategory)) {
            Flash::error('Shop Category not found');

            return redirect(route('shopCategories.index'));
        }

        $this->shopCategoryRepository->delete($id);

        Flash::success('Shop Category deleted successfully.');

        return redirect(route('shopCategories.index'));
    }
}
