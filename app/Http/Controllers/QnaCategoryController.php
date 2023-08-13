<?php

namespace App\Http\Controllers;

use App\DataTables\QnaCategoryDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateQnaCategoryRequest;
use App\Http\Requests\UpdateQnaCategoryRequest;
use App\Models\QnaCategory;
use App\Repositories\QnaCategoryRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index(QnaCategoryDataTable $qnaCategoryDataTable)
    {
        $this->authorize('qna_categories_access');

        return $qnaCategoryDataTable->render('qna_categories.index');
    }

    /**
     * Show the form for creating a new QnaCategory.
     */
    public function create()
    {
        $this->authorize('qna_categories_create');

        $parents = QnaCategory::pluck('name', 'categories_uuid');
        $user    = Auth::user();
        $status  = QnaCategory::STATUS;

        return view('qna_categories.create', compact('user', 'parents', 'status'));
    }

    /**
     * Store a newly created QnaCategory in storage.
     */
    public function store(CreateQnaCategoryRequest $request)
    {
        $this->authorize('qna_categories_create');

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
        $this->authorize('qna_categories_show');

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
        $this->authorize('qna_categories_edit');

        $qnaCategory = $this->qnaCategoryRepository->find($id);

        if (empty($qnaCategory)) {
            Flash::error('Qna Category not found');

            return redirect(route('qnaCategories.index'));
        }

        $user     = Auth::user();
        $parents  = QnaCategory::pluck('name', 'categories_uuid');
        $selfUuid = QnaCategory::where('id', $id)->pluck('name', 'categories_uuid');
        $parents  = $parents->diff($selfUuid);
        $status   = QnaCategory::STATUS;

        return view('qna_categories.edit', compact('qnaCategory', 'user', 'parents', 'status'));
    }

    /**
     * Update the specified QnaCategory in storage.
     */
    public function update($id, UpdateQnaCategoryRequest $request)
    {
        $this->authorize('qna_categories_edit');

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
        $this->authorize('qna_categories_delete');

        $qnaCategory = $this->qnaCategoryRepository->with('qnas')->find($id);

        if (empty($qnaCategory)) {
            Flash::error('Qna Category not found');

            return redirect(route('qnaCategories.index'));
        }

        \DB::transaction(function () use ($qnaCategory, $id) {

            $qnaCategory->qnas()->delete();

            // parent category: remove all child
            $childCategories = QnaCategory::where('parents_uuid', $qnaCategory->categories_uuid)->get();
            if (isset($childCategories)) {
                foreach ($childCategories as $child) {
                    $child->qnas()->delete();
                    $child->delete();
                }
            }

            $this->qnaCategoryRepository->delete($id);

        });

        Flash::success('Qna Category deleted successfully.');

        return redirect(route('qnaCategories.index'));
    }
}
