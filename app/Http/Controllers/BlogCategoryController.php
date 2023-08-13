<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Http\Request;
use Flash;

class BlogCategoryController extends AppBaseController
{
    /** @var BlogCategoryRepository $blogCategoryRepository*/
    private $blogCategoryRepository;

    public function __construct(BlogCategoryRepository $blogCategoryRepo)
    {
        $this->blogCategoryRepository = $blogCategoryRepo;
    }

    /**
     * Display a listing of the BlogCategory.
     */
    public function index(Request $request)
    {
        $blogCategories = $this->blogCategoryRepository->paginate(10);

        return view('blog_categories.index')
            ->with('blogCategories', $blogCategories);
    }

    /**
     * Show the form for creating a new BlogCategory.
     */
    public function create()
    {
        return view('blog_categories.create');
    }

    /**
     * Store a newly created BlogCategory in storage.
     */
    public function store(CreateBlogCategoryRequest $request)
    {
        $input = $request->all();

        $blogCategory = $this->blogCategoryRepository->create($input);

        Flash::success('Blog Category saved successfully.');

        return redirect(route('blogCategories.index'));
    }

    /**
     * Display the specified BlogCategory.
     */
    public function show($id)
    {
        $blogCategory = $this->blogCategoryRepository->find($id);

        if (empty($blogCategory)) {
            Flash::error('Blog Category not found');

            return redirect(route('blogCategories.index'));
        }

        return view('blog_categories.show')->with('blogCategory', $blogCategory);
    }

    /**
     * Show the form for editing the specified BlogCategory.
     */
    public function edit($id)
    {
        $blogCategory = $this->blogCategoryRepository->find($id);

        if (empty($blogCategory)) {
            Flash::error('Blog Category not found');

            return redirect(route('blogCategories.index'));
        }

        return view('blog_categories.edit')->with('blogCategory', $blogCategory);
    }

    /**
     * Update the specified BlogCategory in storage.
     */
    public function update($id, UpdateBlogCategoryRequest $request)
    {
        $blogCategory = $this->blogCategoryRepository->find($id);

        if (empty($blogCategory)) {
            Flash::error('Blog Category not found');

            return redirect(route('blogCategories.index'));
        }

        $blogCategory = $this->blogCategoryRepository->update($request->all(), $id);

        Flash::success('Blog Category updated successfully.');

        return redirect(route('blogCategories.index'));
    }

    /**
     * Remove the specified BlogCategory from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $blogCategory = $this->blogCategoryRepository->find($id);

        if (empty($blogCategory)) {
            Flash::error('Blog Category not found');

            return redirect(route('blogCategories.index'));
        }

        $this->blogCategoryRepository->delete($id);

        Flash::success('Blog Category deleted successfully.');

        return redirect(route('blogCategories.index'));
    }
}
