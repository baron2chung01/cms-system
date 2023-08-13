<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRenovateCasesRequest;
use App\Http\Requests\UpdateRenovateCasesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\RenovateCasesRepository;
use Illuminate\Http\Request;
use Flash;

class RenovateCasesController extends AppBaseController
{
    /** @var RenovateCasesRepository $renovateCasesRepository*/
    private $renovateCasesRepository;

    public function __construct(RenovateCasesRepository $renovateCasesRepo)
    {
        $this->renovateCasesRepository = $renovateCasesRepo;
    }

    /**
     * Display a listing of the RenovateCases.
     */
    public function index(Request $request)
    {
        $renovateCases = $this->renovateCasesRepository->paginate(10);

        return view('renovate_cases.index')
            ->with('renovateCases', $renovateCases);
    }

    /**
     * Show the form for creating a new RenovateCases.
     */
    public function create()
    {
        return view('renovate_cases.create');
    }

    /**
     * Store a newly created RenovateCases in storage.
     */
    public function store(CreateRenovateCasesRequest $request)
    {
        $input = $request->all();

        $renovateCases = $this->renovateCasesRepository->create($input);

        Flash::success('Renovate Cases saved successfully.');

        return redirect(route('renovateCases.index'));
    }

    /**
     * Display the specified RenovateCases.
     */
    public function show($id)
    {
        $renovateCases = $this->renovateCasesRepository->find($id);

        if (empty($renovateCases)) {
            Flash::error('Renovate Cases not found');

            return redirect(route('renovateCases.index'));
        }

        return view('renovate_cases.show')->with('renovateCases', $renovateCases);
    }

    /**
     * Show the form for editing the specified RenovateCases.
     */
    public function edit($id)
    {
        $renovateCases = $this->renovateCasesRepository->find($id);

        if (empty($renovateCases)) {
            Flash::error('Renovate Cases not found');

            return redirect(route('renovateCases.index'));
        }

        return view('renovate_cases.edit')->with('renovateCases', $renovateCases);
    }

    /**
     * Update the specified RenovateCases in storage.
     */
    public function update($id, UpdateRenovateCasesRequest $request)
    {
        $renovateCases = $this->renovateCasesRepository->find($id);

        if (empty($renovateCases)) {
            Flash::error('Renovate Cases not found');

            return redirect(route('renovateCases.index'));
        }

        $renovateCases = $this->renovateCasesRepository->update($request->all(), $id);

        Flash::success('Renovate Cases updated successfully.');

        return redirect(route('renovateCases.index'));
    }

    /**
     * Remove the specified RenovateCases from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $renovateCases = $this->renovateCasesRepository->find($id);

        if (empty($renovateCases)) {
            Flash::error('Renovate Cases not found');

            return redirect(route('renovateCases.index'));
        }

        $this->renovateCasesRepository->delete($id);

        Flash::success('Renovate Cases deleted successfully.');

        return redirect(route('renovateCases.index'));
    }
}
