@extends('layouts.app')

@section('content')

<div class="container">



    <div class="alerts d-none"></div>

    <div class="search-alert">
    </div>


        <input type="text" class="col-md-5" id="search-field" name="search-field"/>
        <button type="button" class="btn btn-primary" id="search-button" >Search</button>
        <span class="search-feedback">
        </span>


    <div class="sort-form">
            <select id="sortCol" name="sortCol">
                <option value='id' selected="true">ID</option>
                <option value='title'>Title</option>
                <option value='description'>Description</option>

            </select>

            <select id="sortOrder" name="sortOrder">
                <option value='ASC' selected="true">ASC</option>
                <option value='DESC'>DESC</option>
            </select>

            {{-- <select id="type_id" name="type_id">
                <option value="all" selected="true"> Show All </option>
            @foreach ($types as $type)
                <option value='{{$type->id}}'>{{$type->title}}</option>
            @endforeach
            </select> --}}
        <button type="button" id="filterTypes" class="btn btn-primary">Filter types</button>

    </div>

    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createtypeModal">
        Create New Artical Modal
    </button>
    {{-- <button class="btn btn-danger" id="delete-selected">Delete Selected</button> --}}

    <table class="table table-bordered table-hover gray types">
        <thead class="thead-dark">
        <tr>
            <th> ID </th>
            <th> Title </th>
            <th> Description</th>
            <th>Action</th>
        </tr>
        </thead>

        @foreach ($types as $type)
        <tr class="rowType{{$type->id}}">
            <td class="colTypeId">{{ $type->id }}</td>
            <td class="colTypeTitle">{{ $type->title }}</td>
            <td class="colTypeDescription">{!! $type->description !!}</td>
            <td>
                <button type="button" class="btn btn-dark show-type" data-type_id='{{$type->id}}'>Show</button>
                <button type="button" class="btn btn-warning update-type" data-type_id='{{$type->id}}'>Update</button>
                <input class="delete-type" type="checkbox"  name="typeDelete[]" value="{{$type->id}}" />
            </td>
        </tr>
        @endforeach
    </table>
    <button class="btn btn-danger" id="delete-selected">Delete Selected</button>
</div>

<div class="modal fade" id="createTypeModal" tabindex="-1" role="dialog" aria-labelledby="createTypeModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="typeAjaxForm">
                <div class="form-group row">
                    <label for="type_title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                    <div class="col-md-6">
                        <input id="type_title" type="text" class="form-control" name="type_title">
                        <span class="invalid-feedback type_title" role="alert"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="type_description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                    <div class="col-md-6">
                        <textarea id="type_description" name="type_description" class="summernote form-control">
                        </textarea>
                        <span class="invalid-feedback type_description" role="alert"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success addTypeModal">Add</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="showTypeModal" tabindex="-1" role="dialog" aria-labelledby="showTypeModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title show-type_title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="show-type_description"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="editTypeModal" tabindex="-1" role="dialog" aria-labelledby="editTypeModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="typeAjaxForm">
                <input type='hidden' id='edit-type_id'>
                <div class="form-group row">
                    <label for="type_title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                    <div class="col-md-6">
                        <input id="edit-type_title" type="text" class="form-control" name="type_title">
                        <span class="invalid-feedback type_title" role="alert"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="type_description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                    <div class="col-md-6">
                        <textarea id="edit-type_description" name="type_description" class="summernote form-control">
                        </textarea>
                        <span class="invalid-feedback type_description" role="alert"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary updateTypeModal">Update</button>
        </div>
      </div>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        }
    });

    function createTable(types){
        $(".types tbody").html("");
        // $(".articles tbody").append("<tr><th>ID</th><th>Title</th><th>Description</th><th>Type</th><th>Actions</th></tr>");
        $.each(types, function(key, type){
            var typeRow =  "<tr class='rowType"+ type.id +"'>";
                typeRow +=  "<td class='colTypeId'>"+ type.id +"</td>";
                typeRow +=  "<td class='colTypeTitle'>"+ type.title +"</td>";
                typeRow +=  "<td class='colTypeDescription'>"+ type.description +"</td>";
                typeRow += "<td>";
                typeRow += '<button type="button" class="btn btn-dark show-type" data-type_id="'+ type.id +'">Show</button>';
                typeRow += '<button type="button" class="btn btn-warning update-type" data-type_id="'+ type.id +'">Update</button>';
                typeRow += '<input class="delete-type" type="checkbox"  name="typeDelete[]"" value="'+ type.id +'"/>';  // patikrinti
                typeRow += "</td>";
                typeRow += "</tr>";
            $(".types tbody").append(typeRow);
        });
    }

    $(document).on('click', '#delete-selected', function() {
    // $("#delete-selected").click(function() {
        var checkedTypes = [];
        $.each( $(".delete-type:checked"), function( key, type) {
            checkedTypes[key] = type.value;
        });
        // console.log(checkedArticles);
            $.ajax({
                type: 'POST',
                url: '{{route("type.destroySelected")}}',
                data: { checkedTypes: checkedTypes},
                success: function(data) {
                        $(".alerts").toggleClass("d-none");
                        for(var i=0; i<data.messages.length; i++) {
                            $(".alerts").append("<div class='alert alert-"+data.errorsuccess[i] + "'><p>"+ data.messages[i] + "</p></div>")
                            var id = data.success[i];
                            if(data.errorsuccess[i] == "success") {
                                $(".rowType"+id ).remove();

                            }
                        }

                    }
                });
            })

        $(".delete-type").click(function(){
            var type_id = $(this).val();

    })


 $(document).ready(function() {
    // $(".addArticleModal").click(function() {
        $(document).on('click', '.addTypeModal', function() {
        var type_title = $("#type_title").val();
        var type_description = $("#type_description").val();

        $.ajax({
                type: 'POST',
                url: '{{route("type.storeAjax")}}',
                data: {type_title:type_title, type_description:type_description},
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#createTypeModal").modal("hide");
                        var typeRow =  "<tr class='type rowType"+ data.type_id +"'>";
                            typeRow +=  "<td class='colTypeId'>"+ data.type_id +"</td>";
                            typeRow +=  "<td class='colTypeTitle'>"+ data.type_title +"</td>";
                            typeRow +=  "<td class='colTypeDescription'>"+ data.type_description +"</td>";
                            typeRow += "<td>";
                            typeRow += '<button type="button" class="btn btn-dark show-type" data-type_id="'+ data.type_id +'">Show</button>';
                            typeRow += '<button type="button" class="btn btn-warning update-type" data-type_id="'+ data.type_id +'">Update</button>';
                            typeRow += '<input class="delete-type" type="checkbox"  name="typeDelete[]"" value="'+ type.id +'"/>';  // patikrinti
                            typeRow += "</td>";
                            typeRow += "</tr>";
                        $(".types tbody").append(typeRow);
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div>");
                        $("#type_title").val('');
                        $("#type_description").val('');
                    } else {
                        $(".invalid-feedback").css("display", 'none');
                        $.each(data.error, function(key, error){
                            var errorSpan = '.' + key;
                            $(errorSpan).css('display', 'block');
                            $(errorSpan).html('');
                            $(errorSpan).append('<strong>'+ error + "</strong>");
                        });
                    }
                }
            });
    });

    // $(".show-article").click(function() {
    $(document).on('click', '.show-type', function() {
       $('#showTypeModal').modal('show');
       var type_id = $(this).attr("data-type_id");
       $.ajax({
                type: 'GET',
                url: '/types/showAjax/' + type_id ,
                success: function(data) {
                    $('.show-type_title').html('');
                    $('.show-type_description').html('');
                    $('.show-type_title').append(data.type_id + '. ' + data.type_title);
                    $('.show-type_description').append(data.type_description);
                }
            });
       console.log(type_id);
    });

    // $(".update-type").click(function() {
        $(document).on('click', '.update-type', function() {

        var type_id = $(this).attr('data-type_id');
        $("#editTypeModal").modal("show");
        $.ajax({
                type: 'GET',
                url: '/types/editAjax/' + type_id ,
                success: function(data) {
                    $("#edit-type_id").val(data.type_id);
                  $("#edit-type_title").val(data.type_title);
                  $("#edit-typedescription").val(data.type_description);
                }
            });
    })

    $(".updateTypeModal").click(function() {
        var type_id = $("#edit-type_id").val();
        var type_title = $("#edit-type_title").val();
        var type_description = $("#edit-type_description").val();
        $.ajax({
                type: 'POST',
                url: '/types/updateAjax/' + type_id ,
                data: {type_title:type_title, type_description:type_description},
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#editTypeModal").modal("hide");
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div");

                            $(".rowType" + type_id + " .colTypeTitle").html(data.type);
                            $(".rowType" + type_id +" .colTypeDescription").html(data.type);
                            $(".rowType" + type_id +" .colTypeTypeTitle").html(data.type);

                    } else {
                        $(".invalid-feedback").css("display", 'none');
                        $.each(data.error, function(key, error){
                            var errorSpan = '.' + key;
                            $(errorSpan).css('display', 'block');
                            $(errorSpan).html('');
                            $(errorSpan).append('<strong>'+ error + "</strong>");
                        });
                    }
                }
            });
    })

    $(document).on('input', '#search-field', function() {
        var searchField = $("#search-field").val();
        var searchFieldCount = searchField.length;
        if(searchFieldCount != 0 && searchFieldCount < 3) {
            $(".search-feedback").css('display', 'block');
            $(".search-feedback").html("Min 3 symbols");
        } else {
            $(".search-feedback").css('display', 'none');
        $.ajax({
                type: 'GET',
                url: '/types/searchAjax/',
                data: {searchField: searchField },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        console.log(data.success);
                        $(".types").css("display", "block");
                        $(".search-alert").html("");
                        $(".search-alert").html(data.success);
                        createTable(data.types);
                    } else {
                        $(".types").css("display", "none");
                        $(".types tbody").html("");
                        $(".search-alert").html("");
                        $(".search-alert").append(data.error);
                    }
                }
            });
        }
    })

    // $(document).on('click', '#filterTypes', function() {
    //     var sortCol = $("#sortCol").val();
    //     var sortOrder = $("#sortOrder").val();
    //     $.ajax({
    //             type: 'GET',
    //             url: '/types/indexAjax/',
    //             data: {sortCol: sortCol, sortOrder: sortOrder},
    //             success: function(data) {
    //                 if($.isEmptyObject(data.error)) {
    //                     createTable(data.types);
    //                 } else {
    //                     console.log(data.error)
    //                 }
    //             }
    //         });
    // });


 });



</script>

@endsection
