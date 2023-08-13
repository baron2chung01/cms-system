<?php

namespace App\Http\Controllers;

use App\DataTables\QuotationDataTable;
use App\Http\Requests\CreateQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\QuotationRepository;
use Illuminate\Http\Request;
use Flash;

class QuotationController extends AppBaseController
{
    /** @var QuotationRepository $quotationRepository*/
    private $quotationRepository;

    public function __construct(QuotationRepository $quotationRepo)
    {
        $this->quotationRepository = $quotationRepo;
    }

    /**
     * Display a listing of the Quotation.
     */
    public function index(QuotationDataTable $quotationDataTable)
    {
        $this->authorize('quotations_access');

        return $quotationDataTable->render('quotations.index');
    }


    /**
     * Show the form for creating a new Quotation.
     */
    public function create()
    {
        $this->authorize('quotations_create');

        return view('quotations.create');
    }

    /**
     * Store a newly created Quotation in storage.
     */
    public function store(CreateQuotationRequest $request)
    {
        $this->authorize('quotations_create');

        $input = $request->all();

        $quotation = $this->quotationRepository->create($input);

        Flash::success('Quotation saved successfully.');

        return redirect(route('quotations.index'));
    }

    /**
     * Display the specified Quotation.
     */
    public function show($id)
    {
        $this->authorize('quotations_show');

        $quotation = $this->quotationRepository->find($id);

        if (empty($quotation)) {
            Flash::error('Quotation not found');

            return redirect(route('quotations.index'));
        }

        return view('quotations.show')->with('quotation', $quotation);
    }

    /**
     * Show the form for editing the specified Quotation.
     */
    public function edit($id)
    {
        $this->authorize('quotations_edit');

        $quotation = $this->quotationRepository->find($id);

        if (empty($quotation)) {
            Flash::error('Quotation not found');

            return redirect(route('quotations.index'));
        }

        return view('quotations.edit')->with('quotation', $quotation);
    }

    /**
     * Update the specified Quotation in storage.
     */
    public function update($id, UpdateQuotationRequest $request)
    {
        $this->authorize('quotations_edit');

        $quotation = $this->quotationRepository->find($id);

        if (empty($quotation)) {
            Flash::error('Quotation not found');

            return redirect(route('quotations.index'));
        }

        $quotation = $this->quotationRepository->update($request->all(), $id);

        Flash::success('Quotation updated successfully.');

        return redirect(route('quotations.index'));
    }

    /**
     * Remove the specified Quotation from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('quotations_delete');

        $quotation = $this->quotationRepository->find($id);

        if (empty($quotation)) {
            Flash::error('Quotation not found');

            return redirect(route('quotations.index'));
        }

        $this->quotationRepository->delete($id);

        Flash::success('Quotation deleted successfully.');

        return redirect(route('quotations.index'));
    }
}
