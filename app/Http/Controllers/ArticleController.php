<?php

namespace App\Http\Controllers;

use Flash;
use App\Models\Assets;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DataTables\ArticleDataTable;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ArticleRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\UpdateArticleRequest;

class ArticleController extends AppBaseController
{
    /** @var ArticleRepository $articleRepository*/
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepo)
    {
        $this->articleRepository = $articleRepo;
    }

    /**
     * Display a listing of the Article.
     */
    public function index(ArticleDataTable $articleDataTable)
    {
        return $articleDataTable->render('articles.index');
    }

    /**
     * Show the form for creating a new Article.
     */
    public function create()
    {
        $user = Auth::user();
        return view('articles.create', compact('user'));
    }

    /**
     * Store a newly created Article in storage.
     */
    public function store(CreateArticleRequest $request)
    {
        $input = $request->all();

        $article = $this->articleRepository->create($input);

        // handle image asset
        $images = $request->file('file-input');

        if (isset($images)) {
            foreach ($images as $image) {
                $imageName = date("Ymdhis") . $image->getClientOriginalName();
                $image->storeAs('public/', $imageName);
                $blogImage = Assets::create([
                    'assets_uuid' => Str::uuid(),
                    'module_uuid' => $article->articles_uuid,
                    'resource_path' => "/storage/{$imageName}",
                    'file_name' => $imageName,
                    'type' => $image->getClientMimeType(),
                    'file_size' => $image->getSize(),
                    'status' => Assets::ACTIVE,
                ]);
            }
        }

        Flash::success('Article saved successfully.');

        return redirect(route('articles.index'));
    }

    /**
     * Display the specified Article.
     */
    public function show($id)
    {
        $article = $this->articleRepository->find($id);

        if (empty($article)) {
            Flash::error('Article not found');

            return redirect(route('articles.index'));
        }

        $images = Assets::where('module_uuid', $article->articles_uuid)->get();

        return view('articles.show', compact('article', 'images'));
    }

    /**
     * Show the form for editing the specified Article.
     */
    public function edit($id)
    {
        $user = Auth::user();

        $article = $this->articleRepository->find($id);

        if (empty($article)) {
            Flash::error('Article not found');

            return redirect(route('articles.index'));
        }

        $images = Assets::where('module_uuid', $article->articles_uuid)->get();

        return view('articles.edit', compact('article', 'user', 'images'));
    }

    /**
     * Update the specified Article in storage.
     */
    public function update($id, UpdateArticleRequest $request)
    {
        $article = $this->articleRepository->find($id);

        if (empty($article)) {
            Flash::error('Article not found');

            return redirect(route('articles.index'));
        }

        $article = $this->articleRepository->update($request->all(), $id);

        Flash::success('Article updated successfully.');

        return redirect(route('articles.index'));
    }

    /**
     * Remove the specified Article from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $article = $this->articleRepository->find($id);

        if (empty($article)) {
            Flash::error('Article not found');

            return redirect(route('articles.index'));
        }

        $this->articleRepository->delete($id);

        Flash::success('Article deleted successfully.');

        return redirect(route('articles.index'));
    }
}