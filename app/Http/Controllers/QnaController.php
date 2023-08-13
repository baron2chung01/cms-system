<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQnaRequest;
use App\Http\Requests\UpdateQnaRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\QnaRepository;
use Illuminate\Http\Request;
use Flash;

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
    public function index(Request $request)
    {
        $qnas = $this->qnaRepository->paginate(10);

        return view('qnas.index')
            ->with('qnas', $qnas);
    }

    /**
     * Show the form for creating a new Qna.
     */
    public function create()
    {
        return view('qnas.create');
    }

    /**
     * Store a newly created Qna in storage.
     */
    public function store(CreateQnaRequest $request)
    {
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
        $qna = $this->qnaRepository->find($id);

        if (empty($qna)) {
            Flash::error('Qna not found');

            return redirect(route('qnas.index'));
        }

        return view('qnas.edit')->with('qna', $qna);
    }

    /**
     * Update the specified Qna in storage.
     */
    public function update($id, UpdateQnaRequest $request)
    {
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
