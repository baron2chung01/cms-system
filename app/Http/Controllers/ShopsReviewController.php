<?php

namespace App\Http\Controllers;

use Flash;
use App\Models\Shop;
use App\Models\Member;
use App\Models\ShopsReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DataTables\ShopsReviewDataTable;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ShopsReviewRepository;
use App\Http\Requests\CreateShopsReviewRequest;
use App\Http\Requests\UpdateShopsReviewRequest;

class ShopsReviewController extends AppBaseController
{
    /** @var ShopsReviewRepository $shopsReviewRepository*/
    private $shopsReviewRepository;

    public function __construct(ShopsReviewRepository $shopsReviewRepo)
    {
        $this->shopsReviewRepository = $shopsReviewRepo;
    }

    /**
     * Display a listing of the ShopsReview.
     */
    public function index(ShopsReviewDataTable $shopsReviewDataTable)
    {
        $this->authorize('shops_reviews_access');

        $shops = Shop::pluck('name');

        $statusList = ShopsReview::DISP_STATUS;

        return $shopsReviewDataTable->render('shops_reviews.index', compact('shops', 'statusList'));
    }

    /**
     * Show the form for creating a new ShopsReview.
     */
    public function create()
    {
        $this->authorize('shops_reviews_create');

        $shops  = Shop::pluck('name', 'shops_uuid');
        $user   = Auth::user();
        $status = ShopsReview::DISP_STATUS;

        return view('shops_reviews.create', compact('shops', 'user', 'status'));
    }

    /**
     * Store a newly created ShopsReview in storage.
     */
    public function store(CreateShopsReviewRequest $request)
    {
        $this->authorize('shops_reviews_create');

        $input           = $request->all();
        // map created_by uuid to client
        $input['rating'] = ($input["product_desc"] + $input["services_quality"] + $input["product_categories"] + $input["logistic_services"] + $input["geographical_location"]) / 5;
        $shopsReview     = $this->shopsReviewRepository->create($input);

        // update shop rating average
        $shop = Shop::where('shops_uuid', $input['shops_uuid'])->first();
        $ratingAvg = $shop->reviews->sum('rating')/$shop->reviews->count();
        $productDescAvg = $shop->reviews->sum('product_desc')/$shop->reviews->count();
        $servicesQualityAvg = $shop->reviews->sum('services_quality')/$shop->reviews->count();
        $productCategoriesAvg = $shop->reviews->sum('product_categories')/$shop->reviews->count();
        $logisticServicesAvg = $shop->reviews->sum('logistic_services')/$shop->reviews->count();
        $geographicalLocationAvg = $shop->reviews->sum('geographical_location')/$shop->reviews->count();

        $shop->update([
            'rating' => round($ratingAvg, 1),
            'product_desc' => round($productDescAvg, 1),
            'services_quality' => round($servicesQualityAvg, 1),
            'product_categories' => round($productCategoriesAvg, 1),
            'logistic_services' => round($logisticServicesAvg, 1),
            'geographical_location' => round($geographicalLocationAvg, 1),
        ]);


        Flash::success('Shops Review saved successfully.');

        return redirect(route('shopsReviews.index'));
    }

    /**
     * Display the specified ShopsReview.
     */
    public function show($id)
    {
        $this->authorize('shops_reviews_show');

        $shopsReview = $this->shopsReviewRepository->find($id);

        if (empty($shopsReview)) {
            Flash::error('Shops Review not found');

            return redirect(route('shopsReviews.index'));
        }

        return view('shops_reviews.show', compact('shopsReview'));
    }

    /**
     * Show the form for editing the specified ShopsReview.
     */
    public function edit($id)
    {
        $this->authorize('shops_reviews_edit');

        $shopsReview = $this->shopsReviewRepository->find($id);

        if (empty($shopsReview)) {
            Flash::error('Shops Review not found');

            return redirect(route('shopsReviews.index'));
        }

        $shops  = Shop::pluck('name', 'shops_uuid');
        $user   = Auth::user();
        $status = ShopsReview::DISP_STATUS;

        return view('shops_reviews.edit', compact('shopsReview', 'shops', 'user', 'status'));
    }

    /**
     * Update the specified ShopsReview in storage.
     */
    public function update($id, UpdateShopsReviewRequest $request)
    {
        $this->authorize('shops_reviews_edit');

        $shopsReview = $this->shopsReviewRepository->find($id);

        if (empty($shopsReview)) {
            Flash::error('Shops Review not found');

            return redirect(route('shopsReviews.index'));
        }

        $input           = $request->all();
        $input['rating'] = ($input["product_desc"] + $input["services_quality"] + $input["product_categories"] + $input["logistic_services"] + $input["geographical_location"]) / 5;

        $shopsReview = $this->shopsReviewRepository->update($input, $id);

        // update shop rating average
        $shop = Shop::where('shops_uuid', $input['shops_uuid'])->first();
        $ratingAvg = $shop->reviews->sum('rating')/$shop->reviews->count();
        $productDescAvg = $shop->reviews->sum('product_desc')/$shop->reviews->count();
        $servicesQualityAvg = $shop->reviews->sum('services_quality')/$shop->reviews->count();
        $productCategoriesAvg = $shop->reviews->sum('product_categories')/$shop->reviews->count();
        $logisticServicesAvg = $shop->reviews->sum('logistic_services')/$shop->reviews->count();
        $geographicalLocationAvg = $shop->reviews->sum('geographical_location')/$shop->reviews->count();

        $shop->update([
            'rating' => round($ratingAvg, 1),
            'product_desc' => round($productDescAvg, 1),
            'services_quality' => round($servicesQualityAvg, 1),
            'product_categories' => round($productCategoriesAvg, 1),
            'logistic_services' => round($logisticServicesAvg, 1),
            'geographical_location' => round($geographicalLocationAvg, 1),
        ]);

        Flash::success('Shops Review updated successfully.');

        return redirect(route('shopsReviews.index'));
    }

    /**
     * Remove the specified ShopsReview from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('shops_reviews_delete');

        $shopsReview = $this->shopsReviewRepository->find($id);

        if (empty($shopsReview)) {
            Flash::error('Shops Review not found');

            return redirect(route('shopsReviews.index'));
        }

        $this->shopsReviewRepository->delete($id);

        // update shop rating average
        $shop = ShopsReview::find($id)->shop;
        $ratingAvg = $shop->reviews->sum('rating')/$shop->reviews->count();
        $productDescAvg = $shop->reviews->sum('product_desc')/$shop->reviews->count();
        $servicesQualityAvg = $shop->reviews->sum('services_quality')/$shop->reviews->count();
        $productCategoriesAvg = $shop->reviews->sum('product_categories')/$shop->reviews->count();
        $logisticServicesAvg = $shop->reviews->sum('logistic_services')/$shop->reviews->count();
        $geographicalLocationAvg = $shop->reviews->sum('geographical_location')/$shop->reviews->count();

        $shop->update([
            'rating' => round($ratingAvg, 1),
            'product_desc' => round($productDescAvg, 1),
            'services_quality' => round($servicesQualityAvg, 1),
            'product_categories' => round($productCategoriesAvg, 1),
            'logistic_services' => round($logisticServicesAvg, 1),
            'geographical_location' => round($geographicalLocationAvg, 1),
        ]);

        Flash::success('Shops Review deleted successfully.');

        return redirect(route('shopsReviews.index'));
    }
}
