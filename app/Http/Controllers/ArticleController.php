<?php

namespace App\Http\Controllers;

use App\Articles\ArticlesRepository;
use App\Models\Article;


class ArticleController extends Controller
{
    public function index(ArticlesRepository $searchRepository)
    {
        $search = request()->has('query') ?
            $searchRepository->search(request('query')) : Article::all();

        return response()->json([
            'result' => $search
        ]);
    }
}
