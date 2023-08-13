<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQnaCategoryRequest;
use App\Http\Requests\UpdateQnaCategoryRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\QnaCategoryRepository;
use Illuminate\Http\Request;
use Flash;

class QnaCategoryController extends AppBaseController
{
    /** @var QnaCategoryRepository $qnaCategoryRepository*/
    private $qnaCategoryRepository;

    public function __construct(QnaCategoryRepository $qnaCategoryRepo)
    {
        $this->qnaCategoryRepository = $qnaCategoryRepo;
    }

    /**
     * Display a listing of the QnaCategory.
     */
    public function index(Request $request)
    {
        $qnaCategories = $this->qnaCategoryRepository->paginate(10);

        return view('qna_categories.index')
            ->with('qnaCategories', $qnaCategories);
    }

    /**
     * Show the form for creating a new QnaCategory.
     */
    public function create()
    {
        return view('qna_categories.create');
    }

    /**
     * Store a newly created QnaCategory in storage.
     */
    public function store(CreateQnaCategoryRequest $request)
    {
        $input = $request->all();

        $qnaCategory = $this->qnaCategoryRepository->create($input);

        Flash::success('Qna Category saved successfully.');

        return redirect(route('qnaCategories.index'));
    }

    /**
     * Display the specified QnaCategory.
     */
    public function show($id)
    {
        $qnaCategory = $this->qnaCategoryRepository->find($id);

        if (empty($qnaCategory)) {
            Flash::error('Qna Category not found');

            return redirect(route('qnaCategories.index'));
        }

        return view('qna_categories.show')->with('qnaCategory', $qnaCategory);
    }

    /**
     * Show the form for editing the specified QnaCategory.
     */
    public function edit($id)
    {
        $qnaCategory = $this->qnaCategoryRepository->find($id);

        if (empty($qnaCategory)) {
            Flash::error('Qna Category not found');

            return redirect(route('qnaCategories.index'));
        }

        return view('qna_categories.edit')->with('qnaCategory', $qnaCategory);
    }

    /**
     * Update the specified QnaCategory in storage.
     */
    public function update($id, UpdateQnaCategoryRequest $request)
    {
        $qnaCategory = $this->qnaCategoryRepository->find($id);

        if (empty($qnaCategory)) {
            Flash::error('Qna Category not found');

            return redirect(route('qnaCategories.index'));
        }

        $qnaCategory = $this->qnaCategoryRepository->update($request->all(), $id);

        Flash::success('Qna Category updated successfully.');

        return redirect(route('qnaCategories.index'));
    }

    /**
     * Remove the specified QnaCategory from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $qnaCategory = $this->qnaCategoryRepository->find($id);

        if (empty($qnaCategory)) {
            Flash::error('Qna Category not found');

            return redirect(route('qnaCategories.index'));
        }

        $this->qnaCategoryRepository->delete($id);

        Flash::success('Qna Category deleted successfully.');

        return redirect(route('qnaCategories.index'));
    }
}
