<?php

namespace App\Http\Controllers;

use App\DataTables\SpecialPromotionDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateSpecialPromotionRequest;
use App\Http\Requests\UpdateSpecialPromotionRequest;
use App\Models\Assets;
use App\Models\Member;
use App\Models\Point;
use App\Models\Shop;
use App\Models\ShopHasSpecialPromotion;
use App\Models\SpecialPromotion;
use App\Repositories\SpecialPromotionRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SpecialPromotionController extends AppBaseController
{
    /** @var SpecialPromotionRepository $specialPromotionRepository*/
    private $specialPromotionRepository;

    public function __construct(SpecialPromotionRepository $specialPromotionRepo)
    {
        $this->specialPromotionRepository = $specialPromotionRepo;
    }

    /**
     * Display a listing of the SpecialPromotion.
     */
    public function index(SpecialPromotionDataTable $specialPromotionDataTable)
    {
        $this->authorize('special_promotions_access');

        return $specialPromotionDataTable->render('special_promotions.index');
    }

    /**
     * Show the form for creating a new SpecialPromotion.
     */
    public function create()
    {
        $this->authorize('special_promotions_create');

        $status = SpecialPromotion::STATUS;
        $shopList = Shop::pluck('name', 'id');
        $shops = collect();

        return view('special_promotions.create', compact('status', 'shopList', 'shops'));
    }

    /**
     * Store a newly created SpecialPromotion in storage.
     */
    public function store(CreateSpecialPromotionRequest $request)
    {
        $this->authorize('special_promotions_create');

        $input = $request->all();

        if (!isset($request->shops)) {
            Flash::error('請為此優惠選擇材料店');
            return redirect()->back();
        }

        $input['short_description'] = 'N/A';
        $specialPromotion = $this->specialPromotionRepository->create($input);

        // handle image asset
        $images = $request->file('file-input');

        if (isset($images)) {
            foreach ($images as $image) {
                $imageName = date("Ymdhis") . $image->getClientOriginalName();
                $imageResized = Image::make($image)->fit(350, 200);
                Storage::disk('public')->put($imageName, $imageResized->encode());

                Assets::create([
                    'assets_uuid'   => Str::uuid(),
                    'module_uuid'   => $specialPromotion->special_promotion_uuid,
                    'module_type'   => SpecialPromotion::BANNER,
                    'resource_path' => "/storage/{$imageName}",
                    'file_name'     => $imageName,
                    'type'          => $image->getClientMimeType(),
                    'file_size'     => $image->getSize(),
                    'status'        => Assets::ACTIVE,
                ]);
            }
        }

        foreach ($request->shops as $shopId) {
            ShopHasSpecialPromotion::create([
                'special_promotion_id' => $specialPromotion->id,
                'shop_id'              => $shopId,
            ]);
        }

        Flash::success('Special Promotion saved successfully.');

        return redirect(route('specialPromotions.index'));
    }

    /**
     * Display the specified SpecialPromotion.
     */
    public function show($id)
    {
        $this->authorize('special_promotions_show');

        $specialPromotion = $this->specialPromotionRepository->find($id);

        if (empty($specialPromotion)) {
            Flash::error('Special Promotion not found');

            return redirect(route('specialPromotions.index'));
        }

        $images = Assets::where('module_uuid', $specialPromotion->special_promotion_uuid)->where('module_type', SpecialPromotion::BANNER)->get();

        $shops = $specialPromotion->shops->pluck('name');

        $receipts = $specialPromotion->receipts()->where('status', Assets::APPROVED)->get();

        return view('special_promotions.show', compact('specialPromotion', 'images', 'shops', 'receipts'));
    }

    /**
     * Display the specified SpecialPromotion Receipt.
     */
    public function showReceipt($id)
    {
        $this->authorize('special_promotions_show');

        $specialPromotion = $this->specialPromotionRepository->find($id);

        if (empty($specialPromotion)) {
            Flash::error('Special Promotion not found');

            return redirect(route('specialPromotions.index'));
        }

        $receipts = $specialPromotion->receipts->sortBy('created_at');

        return view('special_promotions.show-receipt', compact('receipts'));
    }

    /**
     * Show the form for editing the specified SpecialPromotion.
     */
    public function edit($id)
    {
        $this->authorize('special_promotions_edit');

        $specialPromotion = $this->specialPromotionRepository->find($id);

        if (empty($specialPromotion)) {
            Flash::error('Special Promotion not found');

            return redirect(route('specialPromotions.index'));
        }

        $status = SpecialPromotion::STATUS;
        $shopList = Shop::pluck('name', 'id');

        $images = Assets::where('module_uuid', $specialPromotion->special_promotion_uuid)->where('module_type', SpecialPromotion::BANNER)->get();

        $shops = $specialPromotion->shops->pluck('id');

        return view('special_promotions.edit', compact('specialPromotion', 'status', 'images', 'shopList', 'shops'));
    }

    /**
     * Update the specified SpecialPromotion in storage.
     */
    public function update($id, UpdateSpecialPromotionRequest $request)
    {
        $this->authorize('special_promotions_edit');

        $request->validate([
            'shops' => 'required|array',
        ], [
            'shops.required' => '請為此優惠選擇材料店',
            'shops.array'    => 'The shops field must be an array.',
        ]);

        $specialPromotion = $this->specialPromotionRepository->find($id);

        if (empty($specialPromotion)) {
            Flash::error('Special Promotion not found');

            return redirect(route('specialPromotions.index'));
        }

        $input = $request->all();
        $input['short_description'] = 'N/A';

        if (isset($request->shops)) {
            $oriShop = $specialPromotion->shops->pluck('id')->toArray();

            $deleteShop = array_diff($oriShop, $input['shops']);
            $createShop = array_diff($input['shops'], $oriShop);

            foreach ($deleteShop as $shopId) {
                ShopHasSpecialPromotion::where('special_promotion_id', $id)->where('shop_id', $shopId)->forceDelete();
            }
            foreach ($createShop as $shopId) {
                ShopHasSpecialPromotion::create(
                    [
                        'special_promotion_id' => $id,
                        'shop_id'              => $shopId,
                    ]
                );
            }
        }

        $specialPromotion = $this->specialPromotionRepository->update($input, $id);

        Flash::success('Special Promotion updated successfully.');

        return redirect(route('specialPromotions.index'));
    }

    /**
     * Remove the specified SpecialPromotion from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('special_promotions_delete');

        $specialPromotion = $this->specialPromotionRepository->find($id);

        if (empty($specialPromotion)) {
            Flash::error('Special Promotion not found');

            return redirect(route('specialPromotions.index'));
        }

        $this->specialPromotionRepository->delete($id);

        Flash::success('Special Promotion deleted successfully.');

        return redirect(route('specialPromotions.index'));
    }

    public function handleReceipt(Request $request)
    {
        $this->authorize('special_promotions_edit');

        $asset = Assets::where('module_uuid', $request->module_uuid)->where('second_module_uuid', $request->second_module_uuid)
            ->where('created_by', $request->created_by)->first();

        $specialPromotion = SpecialPromotion::where('special_promotion_uuid', $request->module_uuid)->first();

        $member = Member::where('members_uuid', $request->created_by)->first();

        if (empty($asset) || empty($specialPromotion) || empty($member)) {
            Flash::error('Receipt not found.');
        } else {
            // add points to member if approve
            if ($request->approve && isset($specialPromotion->value)) {
                $asset->update([
                    'status' => Assets::APPROVED,
                ]);

                $member->client->update([
                    'points' => $member->client->points + $specialPromotion->value,
                ]);
                Point::create([
                    'members_uuid' => $member->members_uuid,
                    'type'         => Point::ADD,
                    'created_by'   => auth()->user()->id,
                    'updated_by'   => auth()->user()->id,
                    'amount'       => $specialPromotion->value,
                    'remark'       => '使用優惠 (編號:' . $specialPromotion->code . ')',
                ]);
                Flash::success('接受收據，加積分成功');

            } else if (!$request->approve) {
                // set asset to inactive
                $asset->update([
                    'status' => Assets::REJECTED,
                ]);
                Flash::success('拒絕收據');

            } else {
                Flash::error('優惠未有積分數值');
            }

            return redirect(route('specialPromotions.showReceipt', $specialPromotion->id));
        }
    }
}
