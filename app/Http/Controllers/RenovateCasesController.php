<?php

namespace App\Http\Controllers;

use Flash;
use App\Models\Assets;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DataTables\RenovateCasesDataTable;
use App\Http\Controllers\AppBaseController;
use App\Repositories\RenovateCasesRepository;
use App\Http\Requests\CreateRenovateCasesRequest;
use App\Http\Requests\UpdateRenovateCasesRequest;

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
    public function index(RenovateCasesDataTable $renovateCasesDataTable)
    {
        return $renovateCasesDataTable->render('renovate_cases.index');
    }

    /**
     * Show the form for creating a new RenovateCases.
     */
    public function create()
    {
        $user = Auth::user();
        return view('renovate_cases.create', compact('user'));
    }

    /**
     * Store a newly created RenovateCases in storage.
     */
    public function store(CreateRenovateCasesRequest $request)
    {
        $input = $request->all();

        $renovateCases = $this->renovateCasesRepository->create($input);

        // handle image asset
        $images = $request->file('file-input');

        if (isset($images)) {
            foreach ($images as $image) {
                $imageName = date("Ymdhis") . $image->getClientOriginalName();
                $image->storeAs('public/', $imageName);
                $blogImage = Assets::create([
                    'assets_uuid' => Str::uuid(),
                    'module_uuid' => $renovateCases->renovate_cases_uuid,
                    'resource_path' => "/storage/{$imageName}",
                    'file_name' => $imageName,
                    'type' => $image->getClientMimeType(),
                    'file_size' => $image->getSize(),
                    'status' => Assets::ACTIVE,
                ]);
            }
        }

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

        $images = Assets::where('module_uuid', $renovateCases->renovate_cases_uuid)->get();

        return view('renovate_cases.show',compact('renovateCases', 'images'));
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
        $user = Auth::user();

        $images = Assets::where('module_uuid', $renovateCases->renovate_cases_uuid)->get();

        return view('renovate_cases.edit', compact('renovateCases', 'user', 'images'));
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