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

    public function showAjax(Type $type) {

        $success = [
            'success' => 'type recieved successfully',
            'type_id' => $type->id,
            'type_title' => $type->title,
            'type_description' => $type->description,
        ];

        $success_json = response()->json($success);

        return $success_json;
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

    public function editAjax(Type $type) {
        $success = [
            'success' => 'Type recieved successfully',
            'type_id' => $type->id,
            'type_title' => $type->title,
            'type_description' => $type->description,
        ];

        $success_json = response()->json($success);

        return $success_json;
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

    public function updateAjax(Request $request, Type $type) {

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
                'success' => 'Type update successfully',
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
            $type = Type::find($type_id);

            $deleteAction = $type->delete();

            if($deleteAction) {
               $errorsuccess[] = 'danger';
               $messages[] = "Type ".$type_id."cannot be deleted because it has Types";

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
            'success' => 'succescc !!!',
            'messages' => $messages,
            'errorsuccess' => $errorsuccess
        ];

        $success_json = response()->json($success);

        return $success_json;

    }

    public function searchAjax(Request $request) {

        $searchValue = $request->searchField;

        $types = Type::query()
        ->where('title', 'like', "%{$searchValue}%")
        ->orWhere('description', 'like', "%{$searchValue}%")
        ->get();

        if($searchValue == '' || count($types)!= 0) {
            $success = [
                'success' => 'Found '.count($types),
                'types' => $types
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


            $types = Type::where($sortCol, $sortOrder)->get();

        $types_count = count($types);

        if ($types_count == 0) {
            $error = [
                'error' => 'There are no types',
            ];
            $error_json = response()->json($error);
            return $error_json;
        }
        $success = [
            'success' => 'types sorted successfuly',
            'types' => $types
        ];
        $success_json = response()->json($success);

        return $success_json;

    }

}
