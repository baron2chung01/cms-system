<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSpecialPromotionRequest;
use App\Http\Requests\UpdateSpecialPromotionRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SpecialPromotionRepository;
use Illuminate\Http\Request;
use Flash;

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
    public function index(Request $request)
    {
        $specialPromotions = $this->specialPromotionRepository->paginate(10);

        return view('special_promotions.index')
            ->with('specialPromotions', $specialPromotions);
    }

    /**
     * Show the form for creating a new SpecialPromotion.
     */
    public function create()
    {
        return view('special_promotions.create');
    }

    /**
     * Store a newly created SpecialPromotion in storage.
     */
    public function store(CreateSpecialPromotionRequest $request)
    {
        $input = $request->all();

        $specialPromotion = $this->specialPromotionRepository->create($input);

        Flash::success('Special Promotion saved successfully.');

        return redirect(route('specialPromotions.index'));
    }

    /**
     * Display the specified SpecialPromotion.
     */
    public function show($id)
    {
        $specialPromotion = $this->specialPromotionRepository->find($id);

        if (empty($specialPromotion)) {
            Flash::error('Special Promotion not found');

            return redirect(route('specialPromotions.index'));
        }

        return view('special_promotions.show')->with('specialPromotion', $specialPromotion);
    }

    /**
     * Show the form for editing the specified SpecialPromotion.
     */
    public function edit($id)
    {
        $specialPromotion = $this->specialPromotionRepository->find($id);

        if (empty($specialPromotion)) {
            Flash::error('Special Promotion not found');

            return redirect(route('specialPromotions.index'));
        }

        return view('special_promotions.edit')->with('specialPromotion', $specialPromotion);
    }

    /**
     * Update the specified SpecialPromotion in storage.
     */
    public function update($id, UpdateSpecialPromotionRequest $request)
    {
        $specialPromotion = $this->specialPromotionRepository->find($id);

        if (empty($specialPromotion)) {
            Flash::error('Special Promotion not found');

            return redirect(route('specialPromotions.index'));
        }

        $specialPromotion = $this->specialPromotionRepository->update($request->all(), $id);

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
        $specialPromotion = $this->specialPromotionRepository->find($id);

        if (empty($specialPromotion)) {
            Flash::error('Special Promotion not found');

            return redirect(route('specialPromotions.index'));
        }

        $this->specialPromotionRepository->delete($id);

        Flash::success('Special Promotion deleted successfully.');

        return redirect(route('specialPromotions.index'));
    }
}
