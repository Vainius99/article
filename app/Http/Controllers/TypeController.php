<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Article;
use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;
use Illuminate\Http\Request;
use Validator;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();
        return view("type.index", ["types" => $types]);
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
     * @param  \App\Http\Requests\StoreTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTypeRequest $request)
    {
        //
    }

    public function storeAjax(Request $request) {


        $type = new Type();

        $input = [
            'type_title' => $request->type_title,
            'type_description' => $request->type_description,

        ];
        $rules = [
            'type_title' => 'required|min:3',
            'type_description' => 'min:15',

        ];

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            $type->title = $request->type_title;
            $type->description = $request->type_description;


            $type->save();

            $success = [
                'success' => 'type added successfully',
                'type_id' => $type->id,
                'type_title' => $type->title,
                'type_description' => $type->description,

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
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTypeRequest  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTypeRequest $request, Type $type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        //
    }

    public function destroySelected(Request $request) {

        $checkedTypes = $request->checkedTypes;

        $messages = array();

        $errorsuccess = array();

        foreach($checkedTypes as $type_id) {
            $type = Type::where("id", $type_id);
            $type = Type::find($type_id);
            $article_count = $type->typeArticle->count();
            if($article_count > 0) {
               $errorsuccess[] = 'danger';
               $messages[] = "Type ".$type_id."cannot be deleted because it has articles";

            } else {
                $deleteAction = $type->delete();
                if($deleteAction) {
                    $errorsuccess[] = 'success';
                    $messages[] = "Type ".$type_id." deleted successfully";
                } else {
                    $messages[] = "Something went wrong";
                    $errorsuccess[] = 'danger';
                }
            }
        }


        $success = [
            'success' => $checkedTypes,
            'messages' => $messages,
            'errorsuccess' => $errorsuccess
        ];

        $success_json = response()->json($success);

        return $success_json;

    }
}
