<?php

namespace App\Http\Controllers;

use Flash;
use App\Models\Blog;
use App\Models\Assets;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\DataTables\BlogDataTable;
use App\Repositories\BlogRepository;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Controllers\AppBaseController;

class BlogController extends AppBaseController
{
    /** @var BlogRepository $blogRepository*/
    private $blogRepository;

    public function __construct(BlogRepository $blogRepo)
    {
        $this->blogRepository = $blogRepo;
    }

    /**
     * Display a listing of the Blog.
     */
    public function index(BlogDataTable $blogDataTable)
    {
        $this->authorize('blogs_access');

        return $blogDataTable->render('blogs.index');
    }

    /**
     * Show the form for creating a new Blog.
     */
    public function create()
    {
        $this->authorize('blogs_create');

        $categories = BlogCategory::pluck('name', 'blog_categories_uuid');
        $user       = Auth::user();
        $status     = Blog::STATUS;

        return view('blogs.create', compact('categories', 'user', 'status'));
    }

    /**
     * Store a newly created Blog in storage.
     */
    public function store(CreateBlogRequest $request)
    {
        $this->authorize('blogs_create');

        $input = $request->all();

        $blog = $this->blogRepository->create($input);

        // handle image asset
        $images = $request->file('file-input');

        if (isset($images)) {
            foreach ($images as $image) {
                $imageName = date("Ymdhis") . $image->getClientOriginalName();
                $imageResized = Image::make($image)->fit(480, 380);
                Storage::disk('public')->put($imageName, $imageResized->encode());

                $blogImage = Assets::create([
                    'assets_uuid'   => Str::uuid(),
                    'module_uuid'   => $blog->blog_uuid,
                    'resource_path' => "/storage/{$imageName}",
                    'file_name'     => $imageName,
                    'type'          => $image->getClientMimeType(),
                    'file_size'     => $image->getSize(),
                    'status'        => Assets::ACTIVE,
                ]);
            }
        }

        Flash::success('Blog saved successfully.');

        return redirect(route('blogs.index'));
    }

    /**
     * Display the specified Blog.
     */
    public function show($id)
    {
        $this->authorize('blogs_show');

        $blog = $this->blogRepository->with('category')->find($id);

        if (empty($blog)) {
            Flash::error('Blog not found');

            return redirect(route('blogs.index'));
        }

        $status = Blog::STATUS;

        $images = Assets::where('module_uuid', $blog->blog_uuid)->get();

        return view('blogs.show', compact('blog', 'status', 'images'));
    }

    /**
     * Show the form for editing the specified Blog.
     */
    public function edit($id)
    {
        $this->authorize('blogs_edit');

        $blog       = $this->blogRepository->find($id);
        $categories = BlogCategory::pluck('name', 'blog_categories_uuid');
        $user       = Auth::user();
        $status     = Blog::STATUS;

        if (empty($blog)) {
            Flash::error('Blog not found');

            return redirect(route('blogs.index'));
        }

        $images = Assets::where('module_uuid', $blog->blog_uuid)->get();

        return view('blogs.edit', compact('blog', 'categories', 'user', 'status', 'images'));
    }

    /**
     * Update the specified Blog in storage.
     */
    public function update($id, UpdateBlogRequest $request)
    {
        $this->authorize('blogs_edit');

        $blog = $this->blogRepository->find($id);

        if (empty($blog)) {
            Flash::error('Blog not found');

            return redirect(route('blogs.index'));
        }

        $blog = $this->blogRepository->update($request->all(), $id);

        Flash::success('Blog updated successfully.');

        return redirect(route('blogs.index'));
    }

    /**
     * Remove the specified Blog from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('blogs_delete');

        $blog = $this->blogRepository->find($id);

        if (empty($blog)) {
            Flash::error('Blog not found');

            return redirect(route('blogs.index'));
        }

        \DB::transaction(function () use ($blog, $id) {
            $blog->update([
                'status'     => Blog::DELETE,
                'deleted_by' => Auth::user()->users_uuid,
            ]);

            $this->blogRepository->delete($id);
        });

        Flash::success('Blog deleted successfully.');

        return redirect(route('blogs.index'));
    }
}
