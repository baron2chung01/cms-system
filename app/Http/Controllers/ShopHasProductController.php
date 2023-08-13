<?php

namespace App\Http\Controllers;

use App\DataTables\ShopHasProductDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateShopHasProductRequest;
use App\Http\Requests\UpdateShopHasProductRequest;
use App\Models\Assets;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopHasProduct;
use App\Repositories\ShopHasProductRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ShopHasProductController extends AppBaseController
{
    /** @var ShopHasProductRepository $shopHasProductRepository*/
    private $shopHasProductRepository;

    public function __construct(ShopHasProductRepository $shopHasProductRepo)
    {
        $this->shopHasProductRepository = $shopHasProductRepo;
    }

    /**
     * Display a listing of the ShopHasProduct.
     */
    public function index(ShopHasProductDataTable $shopHasProductDataTable)
    {
        $this->authorize('shops_has_products_access');

        if (auth()->user()->hasRole('partner')) {
            $shops = auth()->user()->shops()->pluck('name');
        } else {
            $shops = Shop::pluck('name');
        }

        $statusList = ShopHasProduct::DISP_STATUS;

        return $shopHasProductDataTable->render('shop_has_products.index', compact('shops', 'statusList'));
    }

    /**
     * Show the form for creating a new ShopHasProduct.
     */
    public function create()
    {
        $this->authorize('shops_has_products_create');

        $shops = Shop::pluck('name', 'id');
        $products = Product::pluck('name', 'id');

        return view('shop_has_products.create', compact('shops', 'products'));
    }

    /**
     * Store a newly created ShopHasProduct in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('shops_has_products_create');

        $input = $request->all();

        $product = Product::create([
            'product_uuid' => Str::uuid(),
            'name'         => $input['name'],
            'qty'          => $input['qty'] ?? null,
            'price'        => $input['price'] ?? null,
            'description'  => $input['description'],
            'main_product' => $input['main_product'],
        ]);

        $shopHasProduct = $this->shopHasProductRepository->create([
            'product_id' => $product->id,
            'shop_id'    => $input['shop_id'],
            'status'     => $input['status'],
        ]);

        // handle image asset
        $images = $request->file('file-input');

        if (isset($images)) {
            foreach ($images as $image) {
                $imageName = date("Ymdhis") . $image->getClientOriginalName();
                $imageResized = Image::make($image)->fit(670, 425);
                Storage::disk('public')->put($imageName, $imageResized->encode());

                $blogImage = Assets::create([
                    'assets_uuid'   => Str::uuid(),
                    'module_uuid'   => $shopHasProduct->product->product_uuid,
                    'resource_path' => "/storage/{$imageName}",
                    'file_name'     => $imageName,
                    'type'          => $image->getClientMimeType(),
                    'file_size'     => $image->getSize(),
                    'status'        => Assets::ACTIVE,
                ]);
            }
        }

        Flash::success('Shop Has Product saved successfully.');

        return redirect(route('shopHasProducts.edit', $shopHasProduct->id));
    }

    /**
     * Display the specified ShopHasProduct.
     */
    public function show($id)
    {
        $this->authorize('shops_has_products_show');

        $shopHasProduct = $this->shopHasProductRepository->find($id);

        if (empty($shopHasProduct)) {
            Flash::error('Shop Has Product not found');

            return redirect(route('shopHasProducts.index'));
        }

        return view('shop_has_products.show')->with('shopHasProduct', $shopHasProduct);
    }

    /**
     * Show the form for editing the specified ShopHasProduct.
     */
    public function edit($id)
    {
        $this->authorize('shops_has_products_edit');

        $shopHasProduct = $this->shopHasProductRepository->find($id);

        if (empty($shopHasProduct)) {
            Flash::error('Shop Has Product not found');

            return redirect(route('shopHasProducts.index'));
        }

        $shops = Shop::pluck('name', 'id');

        $images = Assets::where('module_uuid', $shopHasProduct->product->product_uuid)->get();

        return view('shop_has_products.edit', compact('shopHasProduct', 'shops', 'images'));
    }

    /**
     * Update the specified ShopHasProduct in storage.
     */
    public function update($id, Request $request)
    {
        $this->authorize('shops_has_products_edit');

        $shopHasProduct = $this->shopHasProductRepository->find($id);

        $input = $request->all();

        if (empty($shopHasProduct)) {
            Flash::error('Shop Has Product not found');

            return redirect(route('shopHasProducts.index'));
        }

        $product = $shopHasProduct->product->update([
            'product_uuid' => $shopHasProduct->product->product_uuid,
            'name'         => $input['name'],
            'qty'          => $input['qty'] ?? null,
            'price'        => $input['price'] ?? null,
            'description'  => $input['description'],
            'main_product' => $input['main_product'],
        ]);

        $shopHasProduct->update([
            'shop_id' => $input['shop_id'],
            'status'  => $input['status'],
        ]);

        Flash::success('Shop Has Product updated successfully.');

        return redirect(route('shopHasProducts.index'));
    }

    /**
     * Remove the specified ShopHasProduct from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('shops_has_products_delete');

        $shopHasProduct = $this->shopHasProductRepository->find($id);

        if (empty($shopHasProduct)) {
            Flash::error('Shop Has Product not found');

            return redirect(route('shopHasProducts.index'));
        }

        $shopHasProduct->product->delete();

        $this->shopHasProductRepository->delete($id);

        Flash::success('Shop Has Product deleted successfully.');

        return redirect(route('shopHasProducts.index'));
    }
}
