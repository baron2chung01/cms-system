<?php

namespace App\Http\Controllers;

use App\DataTables\RenovateCourseDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateRenovateCourseRequest;
use App\Http\Requests\UpdateRenovateCourseRequest;
use App\Models\Assets;
use App\Models\RenovateCourseCategory;
use App\Repositories\RenovateCourseRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RenovateCourseController extends AppBaseController
{
    /** @var RenovateCourseRepository $renovateCourseRepository*/
    private $renovateCourseRepository;

    public function __construct(RenovateCourseRepository $renovateCourseRepo)
    {
        $this->renovateCourseRepository = $renovateCourseRepo;
    }

    /**
     * Display a listing of the RenovateCourse.
     */
    public function index(RenovateCourseDataTable $renovateCourseDataTable)
    {
        $this->authorize('renovate_courses_access');

        return $renovateCourseDataTable->render('renovate_courses.index');
    }

    /**
     * Show the form for creating a new RenovateCourse.
     */
    public function create()
    {
        $this->authorize('renovate_courses_create');

        $categories = RenovateCourseCategory::pluck('name', 'course_categories_uuid');
        return view('renovate_courses.create', compact('categories'));
    }

    /**
     * Store a newly created RenovateCourse in storage.
     */
    public function store(CreateRenovateCourseRequest $request)
    {
        $this->authorize('renovate_courses_create');

        $input = $request->all();

        $renovateCourse = $this->renovateCourseRepository->create($input);

        // handle image asset
        $images = $request->file('file-input');

        if (isset($images)) {
            foreach ($images as $image) {
                $imageName = date("Ymdhis") . $image->getClientOriginalName();
                $image->storeAs('public/', $imageName);
                $blogImage = Assets::create([
                    'assets_uuid'   => Str::uuid(),
                    'module_uuid'   => $renovateCourse->renovate_courses_uuid,
                    'resource_path' => "/storage/{$imageName}",
                    'file_name'     => $imageName,
                    'type'          => $image->getClientMimeType(),
                    'file_size'     => $image->getSize(),
                    'status'        => Assets::ACTIVE,
                ]);
            }
        }

        Flash::success('Renovate Course saved successfully.');

        return redirect(route('renovateCourses.index'));
    }

    /**
     * Display the specified RenovateCourse.
     */
    public function show($id)
    {
        $this->authorize('renovate_courses_show');

        $renovateCourse = $this->renovateCourseRepository->find($id);

        if (empty($renovateCourse)) {
            Flash::error('Renovate Course not found');

            return redirect(route('renovateCourses.index'));
        }

        $images = Assets::where('module_uuid', $renovateCourse->renovate_courses_uuid)->get();

        return view('renovate_courses.show', compact('renovateCourse', 'images'));
    }

    /**
     * Show the form for editing the specified RenovateCourse.
     */
    public function edit($id)
    {
        $this->authorize('renovate_courses_edit');

        $renovateCourse = $this->renovateCourseRepository->find($id);

        if (empty($renovateCourse)) {
            Flash::error('Renovate Course not found');

            return redirect(route('renovateCourses.index'));
        }

        $images = Assets::where('module_uuid', $renovateCourse->renovate_courses_uuid)->get();

        $categories = RenovateCourseCategory::pluck('name', 'course_categories_uuid');

        return view('renovate_courses.edit', compact('renovateCourse', 'images', 'categories'));
    }

    /**
     * Update the specified RenovateCourse in storage.
     */
    public function update($id, UpdateRenovateCourseRequest $request)
    {
        $this->authorize('renovate_courses_edit');

        $renovateCourse = $this->renovateCourseRepository->find($id);

        if (empty($renovateCourse)) {
            Flash::error('Renovate Course not found');

            return redirect(route('renovateCourses.index'));
        }

        $input = $request->all();

        $input['price']            = (int) str_ireplace([',', '$'], '', $input['price']);
        $input['discounted_price'] = (int) str_ireplace([',', '$'], '', $input['discounted_price']);

        $renovateCourse = $this->renovateCourseRepository->update($input, $id);

        Flash::success('Renovate Course updated successfully.');

        return redirect(route('renovateCourses.index'));
    }

    /**
     * Remove the specified RenovateCourse from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('renovate_courses_delete');

        $renovateCourse = $this->renovateCourseRepository->find($id);

        if (empty($renovateCourse)) {
            Flash::error('Renovate Course not found');

            return redirect(route('renovateCourses.index'));
        }

        $this->renovateCourseRepository->delete($id);

        Flash::success('Renovate Course deleted successfully.');

        return redirect(route('renovateCourses.index'));
    }
}
