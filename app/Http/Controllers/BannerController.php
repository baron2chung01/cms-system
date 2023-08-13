<?php

namespace App\Http\Controllers;

use App\DataTables\BannerDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Models\Assets;
use App\Models\Banner;
use App\Repositories\BannerRepository;
use Flash;
use Illuminate\Support\Str;

class BannerController extends AppBaseController
{
    /** @var BannerRepository $bannerRepository*/
    private $bannerRepository;

    public function __construct(BannerRepository $bannerRepo)
    {
        $this->bannerRepository = $bannerRepo;
    }

    /**
     * Display a listing of the Banner.
     */
    public function index(BannerDataTable $bannerDataTable)
    {
        $this->authorize('banners_access');
        return $bannerDataTable->render('banners.index');
    }

    /**
     * Show the form for creating a new Banner.
     */
    public function create()
    {
        $this->authorize('banners_create');

        $status = Banner::STATUS;
        $modules = Banner::MODULES;

        return view('banners.create', compact('status', 'modules'));
    }

    /**
     * Store a newly created Banner in storage.
     */
    public function store(CreateBannerRequest $request)
    {
        $this->authorize('banners_create');

        $input = $request->all();

        $banner = $this->bannerRepository->create($input);

        // handle image asset
        $images = $request->file('file-input');

        if (isset($images)) {
            foreach ($images as $image) {
                $imageName = date("Ymdhis") . $image->getClientOriginalName();
                $image->storeAs('public/', $imageName);
                Assets::create([
                    'assets_uuid' => Str::uuid(),
                    'module_uuid' => $banner->banner_uuid,
                    'resource_path' => "/storage/{$imageName}",
                    'file_name' => $imageName,
                    'type' => $image->getClientMimeType(),
                    'file_size' => $image->getSize(),
                    'status' => Assets::ACTIVE,
                ]);
            }
        }

        Flash::success('Banner saved successfully.');

        return redirect(route('banners.index'));
    }

    /**
     * Display the specified Banner.
     */
    public function show($id)
    {
        $this->authorize('banners_show');

        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('Banner not found');

            return redirect(route('banners.index'));
        }

        $images = Assets::where('module_uuid', $banner->banner_uuid)->get();

        return view('banners.show', compact('banner', 'images'));
    }

    /**
     * Show the form for editing the specified Banner.
     */
    public function edit($id)
    {
        $this->authorize('banners_edit');

        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('Banner not found');

            return redirect(route('banners.index'));
        }

        $status = Banner::STATUS;
        $modules = Banner::MODULES;

        $images = Assets::where('module_uuid', $banner->banner_uuid)->get();

        return view('banners.edit', compact('banner', 'images', 'modules', 'status'));
    }

    /**
     * Update the specified Banner in storage.
     */
    public function update($id, UpdateBannerRequest $request)
    {
        $this->authorize('banners_edit');

        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('Banner not found');

            return redirect(route('banners.index'));
        }

        $banner = $this->bannerRepository->update($request->all(), $id);

        Flash::success('Banner updated successfully.');

        return redirect(route('banners.index'));
    }

    /**
     * Remove the specified Banner from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('banners_delete');

        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('Banner not found');

            return redirect(route('banners.index'));
        }

        $this->bannerRepository->delete($id);

        Flash::success('Banner deleted successfully.');

        return redirect(route('banners.index'));
    }

    // to unset url after asset delete
    public function refreshOnDelete($uuid)
    {
        $this->authorize('banners_delete');

        $banner = Banner::where('banner_uuid', $uuid)->first();

        if (empty($banner)) {
            return $this->sendError('Banner not found');
        }

        $banner->update([
            'url' => array_fill(0, count($banner->assets), ''),
        ]);

        return $this->sendSuccess('Banner successfully refreshed.');

    }
}
