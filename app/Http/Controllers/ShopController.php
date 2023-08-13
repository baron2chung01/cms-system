<?php

namespace App\Http\Controllers;

use App\DataTables\ShopDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\AddressHasLatLong;
use App\Models\Assets;
use App\Models\MaterialsShop;
use App\Models\Region;
use App\Models\Shop;
use App\Models\ShopAddress;
use App\Models\ShopCategory;
use App\Models\ShopHasRegion;
use App\Models\ShopHasSpecialPromotion;
use App\Models\ShopHasUser;
use App\Models\SpecialPromotion;
use App\Models\User;
use App\Repositories\ShopRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ShopController extends AppBaseController
{
    /** @var ShopRepository $shopRepository*/
    private $shopRepository;

    public function __construct(ShopRepository $shopRepo)
    {
        $this->shopRepository = $shopRepo;
    }

    /**
     * Display a listing of the Shop.
     */
    public function index(ShopDataTable $shopDataTable)
    {
        $this->authorize('shops_access');

        $shopCategories = ShopCategory::pluck('name', 'shop_categories_uuid');

        $statusList = Shop::DISP_STATUS;

        return $shopDataTable->render('shops.index', compact('shopCategories', 'statusList'));
    }

    /**
     * Show the form for creating a new Shop.
     */
    public function create()
    {
        $this->authorize('shops_create');

        $regions = Region::get();

        $user = Auth::user();

        $payments = Shop::PAYMENT;

        $shopCategories = ShopCategory::pluck('name', 'shop_categories_uuid');

        $specialPromotions = SpecialPromotion::get();

        $users = User::where('role', User::PARTNER)->get();

        $promoIds = collect();

        $userIds = collect();

        $regionIds = collect();

        $defaultView = 0;

        $addresses = [''];

        return view('shops.create', compact('addresses', 'regions', 'regionIds', 'payments', 'user', 'userIds', 'shopCategories', 'specialPromotions', 'promoIds', 'defaultView', 'users'));
    }

    /**
     * Store a newly created Shop in storage.
     */
    public function store(CreateShopRequest $request)
    {
        $this->authorize('shops_create');

        $input = $request->all();

        $keys = Shop::PAYMENT;
        $methods = array_fill_keys($keys, 0);
        if (isset($request->payments)) {
            foreach ($request->payments as $selectedItem) {
                $methods[$selectedItem] = 1;
            }
        }
        $input['payment_methods'] = json_encode($methods);

        $shop = $this->shopRepository->create($input);

        // handle main
        $mainImages = $request->file('file-input-main');

        if (isset($mainImages)) {
            foreach ($mainImages as $image) {
                $imageName = date("Ymdhis") . $image->getClientOriginalName();
                $imageResized = Image::make($image)->fit(420, 270);
                Storage::disk('public')->put($imageName, $imageResized->encode());
                Assets::create([
                    'assets_uuid'   => Str::uuid(),
                    'module_uuid'   => $shop->shops_uuid,
                    'resource_path' => "/storage/{$imageName}",
                    'file_name'     => $imageName,
                    'type'          => 'main_image',
                    'file_size'     => $image->getSize(),
                    'status'        => Assets::ACTIVE,
                ]);
            }
        }

        // handle other
        $otherImages = $request->file('file-input-other');

        if (isset($otherImages)) {
            foreach ($otherImages as $image) {
                $imageName = date("Ymdhis") . $image->getClientOriginalName();
                $imageResized = Image::make($image)->fit(670, 425);
                Storage::disk('public')->put($imageName, $imageResized->encode());
                Assets::create([
                    'assets_uuid'   => Str::uuid(),
                    'module_uuid'   => $shop->shops_uuid,
                    'resource_path' => "/storage/{$imageName}",
                    'file_name'     => $imageName,
                    'type'          => 'other_image',
                    'file_size'     => $image->getSize(),
                    'status'        => Assets::ACTIVE,
                ]);
            }
        }

        // handle banner asset
        $banners = $request->file('file-input2');

        if (isset($banners)) {
            foreach ($banners as $banner) {
                $bannerName = date("Ymdhis") . $banner->getClientOriginalName();
                $banner->storeAs('public/', $bannerName);
                Assets::create([
                    'assets_uuid'   => Str::uuid(),
                    'module_uuid'   => $shop->shops_uuid,
                    'resource_path' => "/storage/{$bannerName}",
                    'file_name'     => $bannerName,
                    'type'          => 'banner',
                    'file_size'     => $banner->getSize(),
                    'status'        => Assets::ACTIVE,
                ]);
            }
        }

        if (isset($request->special_promotions) && $request->special_promotions[0] != null) {
            foreach ($request->special_promotions as $promoId) {
                ShopHasSpecialPromotion::create(
                    [
                        'shop_id'              => $shop->id,
                        'special_promotion_id' => $promoId,
                    ]
                );
            }
        }

        if (isset($request->users) && $request->users[0] != null) {
            foreach ($request->users as $userId) {
                ShopHasUser::create(
                    [
                        'shop_id' => $shop->id,
                        'user_id' => $userId,
                    ]
                );
            }
        }

        if (isset($request->regions)) {
            foreach ($request->regions as $regionId) {
                ShopHasRegion::create(
                    [
                        'shop_id'   => $shop->id,
                        'region_id' => $regionId,
                    ]
                );
            }
        }

        if (isset($request->addresses)) {
            foreach ($request->addresses as $key => $address) {
                if (isset($address)) {
                    $address = ShopAddress::create(
                        [
                            'shop_id' => $shop->id,
                            'address' => $address,
                        ]
                    );
                    AddressHasLatLong::create([
                        'address_id' => $address->id,
                        'latitude'   => $request->latitudes[$key],
                        'longitude'  => $request->longitudes[$key],
                    ]);
                }
            }
        }

        Flash::success('Shop saved successfully.');

        return redirect(route('shops.index'));
    }

    /**
     * Display the specified Shop.
     */
    public function show($id)
    {
        $this->authorize('shops_show');

        $shop = $this->shopRepository->with('materials_info.materials')->find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('shops.index'));
        }

        $region = $shop->region;
        $shopPM = json_decode($shop->payment_methods);
        $materials_info = $shop->materials_info;
        $mainImages = Assets::where('module_uuid', $shop->shops_uuid)->where('type', 'main_image')->get();
        $otherImages = Assets::where('module_uuid', $shop->shops_uuid)->where('type', 'other_image')->get();
        $banners = Assets::where('module_uuid', $shop->shops_uuid)->where('type', 'banner')->get();
        $products = $shop->products;

        return view('shops.show', compact('shop', 'region', 'shopPM', 'materials_info', 'mainImages', 'otherImages', 'banners', 'products'));
    }

    /**
     * Show the form for editing the specified Shop.
     */
    public function edit($id)
    {
        $this->authorize('shops_edit');

        $shop = $this->shopRepository->find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('shops.index'));
        }
        $regions = Region::get();

        $user = Auth::user();

        $payments = Shop::PAYMENT;

        $userPayMethod = json_decode($shop->payment_methods, true);

        $mainImages = Assets::where('module_uuid', $shop->shops_uuid)->where('type', 'main_image')->get();

        $otherImages = Assets::where('module_uuid', $shop->shops_uuid)->where('type', 'other_image')->get();

        $banners = Assets::where('module_uuid', $shop->shops_uuid)->where('type', 'banner')->get();

        $shopCategories = ShopCategory::pluck('name', 'shop_categories_uuid');

        $specialPromotions = SpecialPromotion::get();

        $promoIds = $shop->specialPromotions->pluck('id');

        $userIds = $shop->users->pluck('id');

        $regionIds = $shop->regions->pluck('id');

        $defaultView = $shop->views;

        $users = User::where('role', User::PARTNER)->get();

        $addresses = count($shop->addresses) ? $shop->addresses : [''];

        return view('shops.edit', compact('addresses', 'shop', 'regions', 'user', 'users', 'userIds', 'regionIds', 'payments', 'userPayMethod', 'mainImages', 'otherImages', 'banners', 'shopCategories', 'specialPromotions', 'promoIds', 'defaultView'));
    }

    /**
     * Update the specified Shop in storage.
     */
    public function update($id, UpdateShopRequest $request)
    {
        $this->authorize('shops_edit');

        $shop = $this->shopRepository->find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('shops.index'));
        }

        $input = $request->all();
        $keys = Shop::PAYMENT;
        $methods = array_fill_keys($keys, 0);
        if (isset($request->payments)) {
            foreach ($request->payments as $selectedItem) {
                $methods[$selectedItem] = 1;
            }
        }
        $input['payment_methods'] = json_encode($methods);

        if (isset($request->special_promotions)) {
            if ($input['special_promotions'][0] == null) {
                unset($input['special_promotions'][0]);
            }
            $oriPromo = $shop->specialPromotions->pluck('id')->toArray();

            $deletePromo = array_diff($oriPromo, $input['special_promotions']);
            $createPromo = array_diff($input['special_promotions'], $oriPromo);

            foreach ($deletePromo as $promoId) {
                ShopHasSpecialPromotion::where('special_promotion_id', $promoId)->where('shop_id', $id)->forceDelete();
            }
            foreach ($createPromo as $promoId) {
                ShopHasSpecialPromotion::create(
                    [
                        'shop_id'              => $id,
                        'special_promotion_id' => $promoId,
                    ]
                );
            }
        }

        if (isset($request->users)) {
            if ($input['users'][0] == null) {
                unset($input['users'][0]);
            }

            $oriUser = $shop->users->pluck('id')->toArray();

            $deleteUser = array_diff($oriUser, $input['users']);
            $createUser = array_diff($input['users'], $oriUser);

            foreach ($deleteUser as $userId) {
                ShopHasUser::where('user_id', $userId)->where('shop_id', $id)->forceDelete();
            }
            foreach ($createUser as $userId) {
                ShopHasUser::create(
                    [
                        'shop_id' => $id,
                        'user_id' => $userId,
                    ]
                );
            }
        }

        if (isset($request->regions)) {

            $oriRegion = $shop->regions->pluck('id')->toArray();

            $deleteRegion = array_diff($oriRegion, $input['regions']);
            $createRegion = array_diff($input['regions'], $oriRegion);

            foreach ($deleteRegion as $regionId) {
                ShopHasRegion::where('region_id', $regionId)->where('shop_id', $id)->forceDelete();
            }
            foreach ($createRegion as $regionId) {
                ShopHasRegion::create(
                    [
                        'shop_id'   => $id,
                        'region_id' => $regionId,
                    ]
                );
            }
        }

        if (isset($request->addresses)) {
            $oriAddress = $shop->addresses->pluck('address')->toArray();

            $deleteAddress = array_diff($oriAddress, $input['addresses']);
            $createAddress = array_diff($input['addresses'], $oriAddress);
            $updateAddress = array_intersect($input['addresses'], $oriAddress);

            foreach ($deleteAddress as $address) {
                $addressQuery = ShopAddress::where('address', $address)->where('shop_id', $id)->first();
                AddressHasLatLong::where('address_id', $addressQuery->id)->forceDelete();
                $addressQuery->forceDelete();
            }
            foreach ($createAddress as $key => $address) {
                if (isset($address)) {
                    $address = ShopAddress::create(
                        [
                            'shop_id' => $id,
                            'address' => $address,
                        ]
                    );
                    AddressHasLatLong::create([
                        'address_id' => $address->id,
                        'latitude'   => $request->latitudes[$key],
                        'longitude'  => $request->longitudes[$key],
                    ]);
                }
            }
            foreach ($updateAddress as $key => $address) {
                if (isset($address)) {
                    $address = ShopAddress::where('address', $address)->where('shop_id', $id)->first();

                    AddressHasLatLong::updateOrCreate([
                        'address_id' => $address->id,
                    ], [
                        'latitude'  => $request->latitudes[$key],
                        'longitude' => $request->longitudes[$key],
                    ]);
                }
            }
        }

        $shop = $this->shopRepository->update($input, $id);

        Flash::success('Shop updated successfully.');

        return redirect(route('shops.index'));
    }

    /**
     * Remove the specified Shop from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('shops_delete');

        $shop = $this->shopRepository->with('materials_info')->find($id);

        if (empty($shop)) {
            Flash::error('Shop not found');

            return redirect(route('shops.index'));
        }

        \DB::transaction(function () use ($shop, $id) {
            $shop->materials_info()->delete();

            $shop->reviews()->delete();

            $this->shopRepository->delete($id);
        });

        Flash::success('Shop deleted successfully.');

        return redirect(route('shops.index'));
    }
}
