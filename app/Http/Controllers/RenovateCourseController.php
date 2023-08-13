<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRenovateCourseRequest;
use App\Http\Requests\UpdateRenovateCourseRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\RenovateCourseRepository;
use Illuminate\Http\Request;
use Flash;

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
    public function index(Request $request)
    {
        $renovateCourses = $this->renovateCourseRepository->paginate(10);

        return view('renovate_courses.index')
            ->with('renovateCourses', $renovateCourses);
    }

    /**
     * Show the form for creating a new RenovateCourse.
     */
    public function create()
    {
        return view('renovate_courses.create');
    }

    /**
     * Store a newly created RenovateCourse in storage.
     */
    public function store(CreateRenovateCourseRequest $request)
    {
        $input = $request->all();

        $renovateCourse = $this->renovateCourseRepository->create($input);

        Flash::success('Renovate Course saved successfully.');

        return redirect(route('renovateCourses.index'));
    }

    /**
     * Display the specified RenovateCourse.
     */
    public function show($id)
    {
        $renovateCourse = $this->renovateCourseRepository->find($id);

        if (empty($renovateCourse)) {
            Flash::error('Renovate Course not found');

            return redirect(route('renovateCourses.index'));
        }

        return view('renovate_courses.show')->with('renovateCourse', $renovateCourse);
    }

    /**
     * Show the form for editing the specified RenovateCourse.
     */
    public function edit($id)
    {
        $renovateCourse = $this->renovateCourseRepository->find($id);

        if (empty($renovateCourse)) {
            Flash::error('Renovate Course not found');

            return redirect(route('renovateCourses.index'));
        }

        return view('renovate_courses.edit')->with('renovateCourse', $renovateCourse);
    }

    /**
     * Update the specified RenovateCourse in storage.
     */
    public function update($id, UpdateRenovateCourseRequest $request)
    {
        $renovateCourse = $this->renovateCourseRepository->find($id);

        if (empty($renovateCourse)) {
            Flash::error('Renovate Course not found');

            return redirect(route('renovateCourses.index'));
        }

        $renovateCourse = $this->renovateCourseRepository->update($request->all(), $id);

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
