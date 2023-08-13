<?php

namespace App\Http\Controllers;

use App\DataTables\BlogCategoryDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index(BlogCategoryDataTable $blogCategoryDataTable)
    {
        $this->authorize('blog_categories_access');

        return $blogCategoryDataTable->render('blog_categories.index');
    }

    /**
     * Show the form for creating a new BlogCategory.
     */
    public function create()
    {
        $this->authorize('blog_categories_create');

        $user = Auth::user();

        $status = BlogCategory::STATUS;

        $parents = BlogCategory::pluck('name', 'blog_categories_uuid');

        return view('blog_categories.create', compact('user', 'status', 'parents'));
    }

    /**
     * Store a newly created BlogCategory in storage.
     */
    public function store(CreateBlogCategoryRequest $request)
    {
        $this->authorize('blog_categories_create');

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
        $this->authorize('blog_categories_show');

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
        $this->authorize('blog_categories_edit');

        $blogCategory = $this->blogCategoryRepository->find($id);

        $user = Auth::user();

        $status = BlogCategory::STATUS;

        $parents = BlogCategory::pluck('name', 'blog_categories_uuid');

        $selfUuid = BlogCategory::where('id', $id)->pluck('name', 'blog_categories_uuid');

        $parents = $parents->diff($selfUuid);

        if (empty($blogCategory)) {
            Flash::error('Blog Category not found');

            return redirect(route('blogCategories.index'));
        }

        return view('blog_categories.edit', compact('blogCategory', 'user', 'status', 'parents'));
    }

    /**
     * Update the specified BlogCategory in storage.
     */
    public function update($id, UpdateBlogCategoryRequest $request)
    {
        $this->authorize('blog_categories_edit');

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
        $this->authorize('blog_categories_delete');

        $blogCategory = $this->blogCategoryRepository->with('blogs')->find($id);

        if (empty($blogCategory)) {
            Flash::error('Blog Category not found');

            return redirect(route('blogCategories.index'));
        }

        \DB::transaction(function () use ($blogCategory, $id) {
            $blogCategory->update([
                'status'     => BlogCategory::DELETE,
                'deleted_by' => Auth::user()->users_uuid,
            ]);

            $blogCategory->blogs()->delete();

            // parent category: remove all child
            $childCategories = BlogCategory::where('parents_uuid', $blogCategory->blog_categories_uuid)->get();
            if (isset($childCategories)) {
                foreach ($childCategories as $child) {
                    $child->blogs()->delete();
                    $child->delete();
                }
            }

            $this->blogCategoryRepository->delete($id);
        });

        Flash::success('Blog Category deleted successfully.');

        return redirect(route('blogCategories.index'));
    }
}
