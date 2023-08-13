<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShopsReviewRequest;
use App\Http\Requests\UpdateShopsReviewRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ShopsReviewRepository;
use Illuminate\Http\Request;
use Flash;

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
    public function index(Request $request)
    {
        $shopsReviews = $this->shopsReviewRepository->paginate(10);

        return view('shops_reviews.index')
            ->with('shopsReviews', $shopsReviews);
    }

    /**
     * Show the form for creating a new ShopsReview.
     */
    public function create()
    {
        return view('shops_reviews.create');
    }

    /**
     * Store a newly created ShopsReview in storage.
     */
    public function store(CreateShopsReviewRequest $request)
    {
        $input = $request->all();

        $shopsReview = $this->shopsReviewRepository->create($input);

        Flash::success('Shops Review saved successfully.');

        return redirect(route('shopsReviews.index'));
    }

    /**
     * Display the specified ShopsReview.
     */
    public function show($id)
    {
        $shopsReview = $this->shopsReviewRepository->find($id);

        if (empty($shopsReview)) {
            Flash::error('Shops Review not found');

            return redirect(route('shopsReviews.index'));
        }

        return view('shops_reviews.show')->with('shopsReview', $shopsReview);
    }

    /**
     * Show the form for editing the specified ShopsReview.
     */
    public function edit($id)
    {
        $shopsReview = $this->shopsReviewRepository->find($id);

        if (empty($shopsReview)) {
            Flash::error('Shops Review not found');

            return redirect(route('shopsReviews.index'));
        }

        return view('shops_reviews.edit')->with('shopsReview', $shopsReview);
    }

    /**
     * Update the specified ShopsReview in storage.
     */
    public function update($id, UpdateShopsReviewRequest $request)
    {
        $shopsReview = $this->shopsReviewRepository->find($id);

        if (empty($shopsReview)) {
            Flash::error('Shops Review not found');

            return redirect(route('shopsReviews.index'));
        }

        $shopsReview = $this->shopsReviewRepository->update($request->all(), $id);

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
        $shopsReview = $this->shopsReviewRepository->find($id);

        if (empty($shopsReview)) {
            Flash::error('Shops Review not found');

            return redirect(route('shopsReviews.index'));
        }

        $this->shopsReviewRepository->delete($id);

        Flash::success('Shops Review deleted successfully.');

        return redirect(route('shopsReviews.index'));
    }
}
