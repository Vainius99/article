<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Type;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Http\Request;
use Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();
        $articles = Article::all();
        return view("article.index", ["articles" => $articles, 'types' => $types]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        //
    }

    public function storeAjax(Request $request) {


        $article = new Article;

        $input = [
            'article_title' => $request->article_title,
            'article_description' => $request->article_description,
            'article_type_id' => $request->article_type_id
        ];
        $rules = [
            'article_title' => 'required|min:3',
            'article_description' => 'min:15',
            'article_type_id' => 'numeric'
        ];

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            $article->title = $request->article_title;
            $article->description = $request->article_description;
            $article->type_id = $request->article_type_id;

            $article->save();

            $success = [
                'success' => 'Article added successfully',
                'article_id' => $article->id,
                'article_title' => $article->title,
                'article_description' => $article->description,
                'article_type_id' => $article->articleType->title
            ];

            $success_json = response()->json($success);

            return $success_json;
        }

        $errors = [
            'error' => $validator->messages()->get('*')
        ];

        $errors_json = response()->json($errors);

        return $errors_json;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    public function showAjax(Article $article) {

        $success = [
            'success' => 'Article recieved successfully',
            'article_id' => $article->id,
            'article_title' => $article->title,
            'article_description' => $article->description,
            'article_type_id' => $article->articleType->title
        ];

        $success_json = response()->json($success);

        return $success_json;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}