<?php

namespace App\Http\Controllers;

use App\DataTables\AssetsDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\UpdateAssetsRequest;
use App\Models\Assets;
use App\Models\Blog;
use App\Models\Client;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SpecialPromotion;
use App\Repositories\AssetsRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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
    public function index(AssetsDataTable $assetsDataTable)
    {
        return $assetsDataTable->render('assets.index');
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
    public function store(Request $request)
    {
        // handle image asset
        $input = $request->all();

        $image = $request->file('file');

        $imageName = date("Ymdhis") . $image->getClientOriginalName();

        // $image->storeAs('public/', $imageName);

        if (!isset($request->module_name)) {
            // normal image
            $imageResized = Image::make($image)->fit(640, 480);
            Storage::disk('public')->put($imageName, $imageResized->encode());
            // $imageResized->encode()->storeAs('public/', $imageName);
        } else {
            if ($request->module_name == 'shop') {
                // banner
                if ($request->type == 'banner') {
                    $shop = Shop::where('shops_uuid', $request->module_uuid)->first();
                    if (isset($shop->banner)) {
                        return $this->sendError('最多只能有一張橫幅');
                    }
                    $image->storeAs('public/', $imageName);
                }
                // main image
                else if ($request->type == 'main_image') {
                    $checkImage = Assets::where('module_uuid', $request->module_uuid)->where('type', 'main_image')->get();
                    if (isset($checkImage[0])) {
                        return $this->sendError('最多只能有一張商店資訊相片');
                    }
                    $imageResized = Image::make($image)->fit(420, 270);
                    Storage::disk('public')->put($imageName, $imageResized->encode());

                }

                // other image
                else if ($request->type == 'other_image') {
                    $imageResized = Image::make($image)->fit(670, 425);
                    Storage::disk('public')->put($imageName, $imageResized->encode());
                }

            } else if ($request->module_name == 'special_promotion') {
                $specialPromotion = SpecialPromotion::where('special_promotion_uuid', $request->module_uuid)->first();
                if (isset($specialPromotion->image)) {
                    return $this->sendError('最多只能有一張相片');
                }
                $imageResized = Image::make($image)->fit(350, 200);
                Storage::disk('public')->put($imageName, $imageResized->encode());

            } else if ($request->module_name == 'client') {
                $client = Client::where('clients_uuid', $request->module_uuid)->first();
                if (isset($client->image)) {
                    return $this->sendError('最多只能有一張相片');
                }
                $imageResized = Image::make($image)->fit(80, 80);
                Storage::disk('public')->put($imageName, $imageResized->encode());
            } else if ($request->module_name == 'product') {
                $imageResized = Image::make($image)->fit(670, 425);
                Storage::disk('public')->put($imageName, $imageResized->encode());

            } else if ($request->module_name == 'blog') {
                $blog = Blog::where('blog_uuid', $request->module_uuid)->first();
                if (isset($blog->image)) {
                    return $this->sendError('最多只能有一張相片');
                }
                $imageResized = Image::make($image)->fit(480, 380);
                Storage::disk('public')->put($imageName, $imageResized->encode());

            } else {
                $imageMake = Image::make($image);
                if ($imageMake->width() > 1920) {
                    $imageResized = $imageMake->fit(1920, 408);
                } else {
                    $imageResized = $imageMake;
                }
                Storage::disk('public')->put($imageName, $imageResized->encode());
            }
        }

        if (auth('api')->user()) {
            $memberUuid = auth('api')->user()->members_uuid;
        } else {
            $memberUuid = null;
        }

        $asset = Assets::create([
            'assets_uuid'        => Str::uuid(),
            'module_uuid'        => $input['module_uuid'],
            'second_module_uuid' => $input['second_module_uuid'] ?? null,
            'resource_path'      => "storage/{$imageName}",
            'file_name'          => pathinfo($image->getClientOriginalName())['filename'],
            'type'               => $input['type'],
            'module_type'        => $input['module_type'] ?? null,
            'created_by'         => $memberUuid,
            'file_size'          => $image->getSize(),
            'status'             => Assets::ACTIVE,
        ]);

        Flash::success('Assets saved successfully.');

        return response()->json(['id' => $asset->id, 'module_uuid' => $asset->module_uuid, 'module_type' => $asset->module_type ?? null]);
    }

    public function storeReceipt(Request $request)
    {
        // handle image asset
        $input = $request->all();

        $image = $request->file('file');

        $imageName = date("Ymdhis") . $image->getClientOriginalName();

        $image->storeAs('public/', $imageName);

        if (auth('api')->user()) {
            $memberUuid = auth('api')->user()->members_uuid;
        } else {
            $memberUuid = null;
        }
        $specialPromo = SpecialPromotion::where('special_promotion_uuid', $input['module_uuid'])->first();

        $shop = $specialPromo->shops->first();

        if (empty($shop)) {
            return $this->sendError('請在此優惠選擇材料店');
        }

        $shopUuid = $shop->shops_uuid;

        $assetQuery = Assets::where('module_uuid', $input['module_uuid'])->where('second_module_uuid', $shopUuid)
            ->where('created_by', $memberUuid)->where('status', Assets::APPROVED)->exists();

        if ($assetQuery) {
            return $this->sendError('優惠已被使用');
        }

        $asset = Assets::updateOrCreate([
            'module_uuid'        => $input['module_uuid'],
            'second_module_uuid' => $shopUuid,
            'created_by'         => $memberUuid,
        ], [
            'assets_uuid'   => Str::uuid(),
            'resource_path' => "storage/{$imageName}",
            'file_name'     => pathinfo($image->getClientOriginalName())['filename'],
            'type'          => $input['type'],
            'module_type'   => $input['module_type'] ?? null,
            'file_size'     => $image->getSize(),
            'status'        => Assets::ACTIVE,
        ]);

        return $this->sendSuccess('收據已被上傳');

        // return response()->json(['id' => $asset->id, 'module_uuid' => $asset->module_uuid, 'module_type' => $asset->module_type ?? null]);

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

        Storage::delete(str_replace("/storage", "public", $assets->resource_path));

        $this->assetsRepository->delete($id);

        Flash::success('Assets deleted successfully.');

        return response()->json(['id' => $id, 'module_uuid' => $assets->module_uuid]);
    }

    public function mainImageFileList($module_uuid)
    {
        // $blog     = Blog::find($id);
        // $images   = $blog->images;
        $images = Assets::where('module_uuid', $module_uuid)->where('type', 'main_image')->get();
        $dropzone = "<div class='dropzone' data-module-uuid='{$module_uuid}'>
        <div class='dz-default dz-message'>
        <div class='icon'><i class='fas fa-cloud-upload-alt'></i></div>
        <span class='note'>Drop files here to upload <span class='d-block mb-3'>or</span></span>
        <div class='btn btn-outline-success btn-flat'>Browse Files</div>
        </div>
        </div>";

        $list = '<div class="row">';

        if (!empty($images)) {
            // $images = parseToObj($images);
            foreach ($images as $key => $value) {
                $list .= '<div class="col-sm-6 col-md-4">';

                $url = asset($value->resource_path);

                $list .= '<div class="thumbnail text-center">';

                $list .= "<a class='imgFull d-flex align-items-center justify-content-center flex-column' href='" . $url . "' target='_new'>";

                $list .= '<img id="imgTbn_' . $value->id . '" src="' . $url . '">';

                $list .= "</a>";
                $list .= "<div class='delete-file-cell text-center d-flex flex-wrap'>";
                $list .= '<a onclick="removeFileMain(this)" data-id=' . $value->id . ' data-module-uuid="' . $module_uuid .
                    '" class="btn btn-outline-danger btn-flat m-auto">
                    <i class="fas fa-trash-alt"></i>刪除</a>';
                $list .= "</div>";
                $list .= "</div>";
                $list .= "</div>";
            }
            // if ($key + 1 < $max) {
            // $list .= '<div class="col-sm-6 col-md-4">';
            // $list .= $dropzone;
            // }
        } else {
            $list .= '<div class="col-sm-6 col-md-4">';
            $list .= $dropzone;
        }

        $list .= "</div>";

        $resp["list"] = $list;
        $resp["module_uuid"] = $module_uuid;

        return response()->json($resp);
    }
    public function otherImageFileList($module_uuid)
    {
        // $blog     = Blog::find($id);
        // $images   = $blog->images;
        $images = Assets::where('module_uuid', $module_uuid)->where('type', 'other_image')->get();
        $dropzone = "<div class='dropzone' data-module-uuid='{$module_uuid}'>
        <div class='dz-default dz-message'>
        <div class='icon'><i class='fas fa-cloud-upload-alt'></i></div>
        <span class='note'>Drop files here to upload <span class='d-block mb-3'>or</span></span>
        <div class='btn btn-outline-success btn-flat'>Browse Files</div>
        </div>
        </div>";

        $list = '<div class="row">';

        if (!empty($images)) {
            // $images = parseToObj($images);
            foreach ($images as $key => $value) {
                $list .= '<div class="col-sm-6 col-md-4">';

                $url = asset($value->resource_path);

                $list .= '<div class="thumbnail text-center">';

                $list .= "<a class='imgFull d-flex align-items-center justify-content-center flex-column' href='" . $url . "' target='_new'>";

                $list .= '<img id="imgTbn_' . $value->id . '" src="' . $url . '">';

                $list .= "</a>";
                $list .= "<div class='delete-file-cell text-center d-flex flex-wrap'>";
                $list .= '<a onclick="removeFileOther(this)" data-id=' . $value->id . ' data-module-uuid="' . $module_uuid .
                    '" class="btn btn-outline-danger btn-flat m-auto">
                    <i class="fas fa-trash-alt"></i>刪除</a>';
                $list .= "</div>";
                $list .= "</div>";
                $list .= "</div>";
            }
            // if ($key + 1 < $max) {
            // $list .= '<div class="col-sm-6 col-md-4">';
            // $list .= $dropzone;
            // }
        } else {
            $list .= '<div class="col-sm-6 col-md-4">';
            $list .= $dropzone;
        }

        $list .= "</div>";

        $resp["list"] = $list;
        $resp["module_uuid"] = $module_uuid;

        return response()->json($resp);
    }

    public function imageFileList($module_uuid)
    {
        // $blog     = Blog::find($id);
        // $images   = $blog->images;
        $images = Assets::where('module_uuid', $module_uuid)->where('type', 'LIKE', '%image%')->get();
        $dropzone = "<div class='dropzone' data-module-uuid='{$module_uuid}'>
        <div class='dz-default dz-message'>
        <div class='icon'><i class='fas fa-cloud-upload-alt'></i></div>
        <span class='note'>Drop files here to upload <span class='d-block mb-3'>or</span></span>
        <div class='btn btn-outline-success btn-flat'>Browse Files</div>
        </div>
        </div>";

        $list = '<div class="row">';

        if (!empty($images)) {
            // $images = parseToObj($images);
            foreach ($images as $key => $value) {
                $list .= '<div class="col-sm-6 col-md-4">';

                $url = asset($value->resource_path);

                $list .= '<div class="thumbnail text-center">';

                $list .= "<a class='imgFull d-flex align-items-center justify-content-center flex-column' href='" . $url . "' target='_new'>";

                $list .= '<img id="imgTbn_' . $value->id . '" src="' . $url . '">';

                $list .= "</a>";
                $list .= "<div class='delete-file-cell text-center d-flex flex-wrap'>";
                $list .= '<a onclick="removeFile(this)" data-id=' . $value->id . ' data-module-uuid="' . $module_uuid .
                    '" class="btn btn-outline-danger btn-flat m-auto">
                    <i class="fas fa-trash-alt"></i>刪除</a>';
                $list .= "</div>";
                $list .= "</div>";
                $list .= "</div>";
            }
            // if ($key + 1 < $max) {
            // $list .= '<div class="col-sm-6 col-md-4">';
            // $list .= $dropzone;
            // }
        } else {
            $list .= '<div class="col-sm-6 col-md-4">';
            $list .= $dropzone;
        }

        $list .= "</div>";

        $resp["list"] = $list;
        $resp["module_uuid"] = $module_uuid;

        return response()->json($resp);
    }

    public function imageFileListOfType($module_uuid, $module_type)
    {
        // $blog     = Blog::find($id);
        // $images   = $blog->images;
        $images = Assets::where('module_uuid', $module_uuid)->where('type', 'LIKE', '%image%')->where('module_type', $module_type)->get();
        $dropzone = "<div class='dropzone' data-module-uuid='{$module_uuid}'>
        <div class='dz-default dz-message'>
        <div class='icon'><i class='fas fa-cloud-upload-alt'></i></div>
        <span class='note'>Drop files here to upload <span class='d-block mb-3'>or</span></span>
        <div class='btn btn-outline-success btn-flat'>Browse Files</div>
        </div>
        </div>";

        $list = '<div class="row">';

        if (!empty($images)) {
            // $images = parseToObj($images);
            foreach ($images as $key => $value) {
                $list .= '<div class="col-sm-6 col-md-4">';

                $url = asset($value->resource_path);

                $list .= '<div class="thumbnail text-center">';

                $list .= "<a class='imgFull d-flex align-items-center justify-content-center flex-column' href='" . $url . "' target='_new'>";

                $list .= '<img id="imgTbn_' . $value->id . '" src="' . $url . '">';

                $list .= "</a>";
                $list .= "<div class='delete-file-cell text-center d-flex flex-wrap'>";
                $list .= '<a onclick="removeFile(this)" data-id=' . $value->id . ' data-module-uuid="' . $module_uuid .
                    '" class="btn btn-outline-danger btn-flat m-auto">
                    <i class="fas fa-trash-alt"></i>刪除</a>';
                $list .= "</div>";
                $list .= "</div>";
                $list .= "</div>";
            }
            // if ($key + 1 < $max) {
            // $list .= '<div class="col-sm-6 col-md-4">';
            // $list .= $dropzone;
            // }
        } else {
            $list .= '<div class="col-sm-6 col-md-4">';
            $list .= $dropzone;
        }

        $list .= "</div>";

        $resp["list"] = $list;
        $resp["module_uuid"] = $module_uuid;

        return response()->json($resp);
    }

    public function bannerFileList($module_uuid)
    {
        // $blog     = Blog::find($id);
        // $images   = $blog->images;
        $images = Assets::where('module_uuid', $module_uuid)->where('type', 'banner')->get();
        $dropzone = "<div class='dropzone' data-module-uuid='{$module_uuid}'>
        <div class='dz-default dz-message'>
        <div class='icon'><i class='fas fa-cloud-upload-alt'></i></div>
        <span class='note'>Drop files here to upload <span class='d-block mb-3'>or</span></span>
        <div class='btn btn-outline-success btn-flat'>Browse Files</div>
        </div>
        </div>";

        $list = '<div class="row">';

        if (!empty($images)) {
            // $images = parseToObj($images);
            foreach ($images as $key => $value) {
                $list .= '<div class="col-sm-6 col-md-4">';

                $url = asset($value->resource_path);

                $list .= '<div class="thumbnail text-center">';

                $list .= "<a class='imgFull d-flex align-items-center justify-content-center flex-column' href='" . $url . "' target='_new'>";

                $list .= '<img id="imgTbn_' . $value->id . '" src="' . $url . '">';

                $list .= "</a>";
                $list .= "<div class='delete-file-cell text-center d-flex flex-wrap'>";
                $list .= '<a onclick="removeFile2(this)" data-id=' . $value->id . ' data-module-uuid="' . $module_uuid .
                    '" class="btn btn-outline-danger btn-flat m-auto">
                    <i class="fas fa-trash-alt"></i>Delete</a>';
                $list .= "</div>";
                $list .= "</div>";
                $list .= "</div>";
            }
            // if ($key + 1 < $max) {
            // $list .= '<div class="col-sm-6 col-md-4">';
            // $list .= $dropzone;
            // }
        } else {
            $list .= '<div class="col-sm-6 col-md-4">';
            $list .= $dropzone;
        }

        $list .= "</div>";

        $resp["list"] = $list;
        $resp["module_uuid"] = $module_uuid;

        return response()->json($resp);
    }
}
