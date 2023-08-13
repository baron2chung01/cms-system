<?php

namespace App\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use App\Models\RenovateCourseCategory;
use App\Http\Controllers\AppBaseController;
use App\DataTables\RenovateCourseCategoryDataTable;
use App\Repositories\RenovateCourseCategoryRepository;
use App\Http\Requests\CreateRenovateCourseCategoryRequest;
use App\Http\Requests\UpdateRenovateCourseCategoryRequest;

class RenovateCourseCategoryController extends AppBaseController
{
    /** @var RenovateCourseCategoryRepository $renovateCourseCategoryRepository*/
    private $renovateCourseCategoryRepository;

    public function __construct(RenovateCourseCategoryRepository $renovateCourseCategoryRepo)
    {
        $this->renovateCourseCategoryRepository = $renovateCourseCategoryRepo;
    }

    /**
     * Display a listing of the RenovateCourseCategory.
     */
    public function index(RenovateCourseCategoryDataTable $renovateCourseCategoryDataTable)
    {
        $this->authorize('renovate_course_categories_access');

    return $renovateCourseCategoryDataTable->render('renovate_course_categories.index');
    }


    /**
     * Show the form for creating a new RenovateCourseCategory.
     */
    public function create()
    {
        $this->authorize('renovate_course_categories_create');

        $status = RenovateCourseCategory::STATUS;
        $parents = RenovateCourseCategory::pluck('name', 'course_categories_uuid');

        return view('renovate_course_categories.create', compact('parents', 'status'));
    }

    /**
     * Store a newly created RenovateCourseCategory in storage.
     */
    public function store(CreateRenovateCourseCategoryRequest $request)
    {
        $this->authorize('renovate_course_categories_create');

        $input = $request->all();

        $renovateCourseCategory = $this->renovateCourseCategoryRepository->create($input);

        Flash::success('Renovate Course Category saved successfully.');

        return redirect(route('renovateCourseCategories.index'));
    }

    /**
     * Display the specified RenovateCourseCategory.
     */
    public function show($id)
    {
        $this->authorize('renovate_course_categories_show');

        $renovateCourseCategory = $this->renovateCourseCategoryRepository->find($id);

        if (empty($renovateCourseCategory)) {
            Flash::error('Renovate Course Category not found');

            return redirect(route('renovateCourseCategories.index'));
        }

        return view('renovate_course_categories.show')->with('renovateCourseCategory', $renovateCourseCategory);
    }

    /**
     * Show the form for editing the specified RenovateCourseCategory.
     */
    public function edit($id)
    {
        $this->authorize('renovate_course_categories_edit');

        $renovateCourseCategory = $this->renovateCourseCategoryRepository->find($id);

        if (empty($renovateCourseCategory)) {
            Flash::error('Renovate Course Category not found');

            return redirect(route('renovateCourseCategories.index'));
        }

        $status = RenovateCourseCategory::STATUS;
        $parents  = RenovateCourseCategory::pluck('name', 'course_categories_uuid');
        $selfUuid = RenovateCourseCategory::where('id', $id)->pluck('name', 'course_categories_uuid');
        $parents  = $parents->diff($selfUuid);

        return view('renovate_course_categories.edit',compact('renovateCourseCategory', 'status', 'parents'));
    }

    /**
     * Update the specified RenovateCourseCategory in storage.
     */
    public function update($id, UpdateRenovateCourseCategoryRequest $request)
    {
        $this->authorize('renovate_course_categories_edit');

        $renovateCourseCategory = $this->renovateCourseCategoryRepository->find($id);

        if (empty($renovateCourseCategory)) {
            Flash::error('Renovate Course Category not found');

            return redirect(route('renovateCourseCategories.index'));
        }

        $renovateCourseCategory = $this->renovateCourseCategoryRepository->update($request->all(), $id);

        Flash::success('Renovate Course Category updated successfully.');

        return redirect(route('renovateCourseCategories.index'));
    }

    /**
     * Remove the specified RenovateCourseCategory from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('renovate_course_categories_delete');

        $renovateCourseCategory = $this->renovateCourseCategoryRepository->find($id);

        if (empty($renovateCourseCategory)) {
            Flash::error('Renovate Course Category not found');

            return redirect(route('renovateCourseCategories.index'));
        }

        $this->renovateCourseCategoryRepository->delete($id);

        Flash::success('Renovate Course Category deleted successfully.');

        return redirect(route('renovateCourseCategories.index'));
    }
}
