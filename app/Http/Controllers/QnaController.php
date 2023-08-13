<?php

namespace App\Http\Controllers;

use App\DataTables\QnaDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateQnaRequest;
use App\Http\Requests\UpdateQnaRequest;
use App\Models\Qna;
use App\Models\QnaCategory;
use App\Repositories\QnaRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QnaController extends AppBaseController
{
    /** @var QnaRepository $qnaRepository*/
    private $qnaRepository;

    public function __construct(QnaRepository $qnaRepo)
    {
        $this->qnaRepository = $qnaRepo;
    }

    /**
     * Display a listing of the Qna.
     */
    public function index(QnaDataTable $qnaDataTable)
    {
        $this->authorize('qna_access');

        return $qnaDataTable->render('qnas.index');
    }

    /**
     * Show the form for creating a new Qna.
     */
    public function create()
    {
        $this->authorize('qna_create');

        $user       = Auth::user();
        $categories = QnaCategory::pluck('name', 'categories_uuid');
        $status     = Qna::STATUS;
        return view('qnas.create', compact('user', 'categories', 'status'));
    }

    /**
     * Store a newly created Qna in storage.
     */
    public function store(CreateQnaRequest $request)
    {
        $this->authorize('qna_create');

        $input = $request->all();

        $qna = $this->qnaRepository->create($input);

        Flash::success('Qna saved successfully.');

        return redirect(route('qnas.index'));
    }

    /**
     * Display the specified Qna.
     */
    public function show($id)
    {
        $this->authorize('qna_show');

        $qna = $this->qnaRepository->find($id);

        if (empty($qna)) {
            Flash::error('Qna not found');

            return redirect(route('qnas.index'));
        }

        return view('qnas.show')->with('qna', $qna);
    }

    /**
     * Show the form for editing the specified Qna.
     */
    public function edit($id)
    {
        $this->authorize('qna_edit');

        $qna = $this->qnaRepository->find($id);

        if (empty($qna)) {
            Flash::error('Qna not found');

            return redirect(route('qnas.index'));
        }

        $user       = Auth::user();
        $categories = QnaCategory::pluck('name', 'categories_uuid');
        $status     = Qna::STATUS;

        return view('qnas.edit', compact('qna', 'categories', 'status', 'user'));
    }

    /**
     * Update the specified Qna in storage.
     */
    public function update($id, UpdateQnaRequest $request)
    {
        $this->authorize('qna_edit');

        $qna = $this->qnaRepository->find($id);

        if (empty($qna)) {
            Flash::error('Qna not found');

            return redirect(route('qnas.index'));
        }

        $qna = $this->qnaRepository->update($request->all(), $id);

        Flash::success('Qna updated successfully.');

        return redirect(route('qnas.index'));
    }

    /**
     * Remove the specified Qna from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('qna_delete');

        $qna = $this->qnaRepository->find($id);

        if (empty($qna)) {
            Flash::error('Qna not found');

            return redirect(route('qnas.index'));
        }

        $this->qnaRepository->delete($id);

        Flash::success('Qna deleted successfully.');

        return redirect(route('qnas.index'));
    }
}
