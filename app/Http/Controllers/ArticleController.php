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

    public function editAjax(Article $article) {
        $success = [
            'success' => 'Article recieved successfully',
            'article_id' => $article->id,
            'article_title' => $article->title,
            'article_description' => $article->description,
            'article_type_id' => $article->type_id
        ];

        $success_json = response()->json($success);

        return $success_json;
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

    public function updateAjax(Request $request, Article $article) {

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
                'success' => 'Article update successfully',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }

    public function destroySelected(Request $request) {

        $checkedArticles = $request->checkedArticles;

        $messages = array();

        $errorsuccess = array();

        foreach($checkedArticles as $article_id) {

            $article = Article::find($article_id);
                $deleteAction = $article->delete();
                if($deleteAction) {
                    $errorsuccess[] = 'success';
                    $messages[] = "Article ".$article_id." deleted successfully";
                } else {
                    $messages[] = "Something went wrong";
                    $errorsuccess[] = 'danger';
                }
        }
        $success = [
            'success' => $checkedArticles,
            'messages' => $messages,
            'errorsuccess' => $errorsuccess
        ];

        $success_json = response()->json($success);

        return $success_json;

    }


        public function searchAjax(Request $request) {

        $searchValue = $request->searchField;

        $articles = Article::query()
        ->where('title', 'like', "%{$searchValue}%")
        ->orWhere('description', 'like', "%{$searchValue}%")
        ->get();

        foreach ($articles as $article) {
            $article['articleType'] = $article->articleType->title;
        }

        if($searchValue == '' || count($articles)!= 0) {
            $success = [
                'success' => 'Found '.count($articles),
                'articles' => $articles
            ];

            $success_json = response()->json($success);

            return $success_json;
        }

        $error = [
            'error' => 'No results are found'
        ];

        $errors_json = response()->json($error);

        return $errors_json;

    }

    public function indexAjax(Request $request) {

        $sortCol = $request->sortCol;
        $sortOrder = $request->sortOrder;
        $type_id = $request->type_id;

        if($type_id == 'all') {
            $articles = Article::orderBy($sortCol, $sortOrder)->get();
        } else {
            $articles = Article::where('type_id', $type_id)->orderBy($sortCol, $sortOrder)->get();
        }

        foreach ($articles as $article) {
            $article['type_title'] = $article->articleType->title;
        }
        $articles_count = count($articles);

        if ($articles_count == 0) {
            $error = [
                'error' => 'There are no articles',
            ];
            $error_json = response()->json($error);
            return $error_json;
        }
        $success = [
            'success' => 'articles sorted successfuly',
            'articles' => $articles
        ];
        $success_json = response()->json($success);

        return $success_json;

    }

    // public function filterAjax(Request $request) {

    //     $type_id = $request->type_id;

    //     if($type_id == 'all') {
    //         $articles = Article::all();
    //     } else {
    //         $articles = Article::all()->where('type_id', $type_id);
    //     }

    //     foreach ($articles as $article) {
    //         $article['type_title'] = $article->articleType->title;
    //     }

    //     $articles_count = count($articles);

    //     if ($articles_count == 0) {
    //         $error = [
    //             'error' => 'There are no articles',
    //         ];

    //         $error_json = response()->json($error);
    //         return $error_json;
    //     }

    //     $success = [
    //         'success' => 'articles filtered successfuly',
    //         'articles' => $articles
    //     ];
    //     $success_json = response()->json($success);
    //     return $success_json;
    // }
}
