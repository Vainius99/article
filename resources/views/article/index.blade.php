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
                <option value='type_id'>Type</option>
            </select>

            <select id="sortOrder" name="sortOrder">
                <option value='ASC' selected="true">ASC</option>
                <option value='DESC'>DESC</option>
            </select>

            <select id="type_id" name="type_id">
                <option value="all" selected="true"> Show All </option>
            @foreach ($types as $type)
                <option value='{{$type->id}}'>{{$type->title}}</option>
            @endforeach
            </select>
        <button type="button" id="filterArticles" class="btn btn-primary">Filter Articles</button>

    </div>

    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createArticleModal">
        Create New Client Modal
    </button>
    {{-- <button class="btn btn-danger" id="delete-selected">Delete Selected</button> --}}

    <table class="table table-bordered table-hover gray articles">
        <thead class="thead-dark">
        <tr>
            <th> ID </th>
            <th> Title </th>
            <th> Description</th>
            <th> Type</th>
            <th>Action</th>
        </tr>
        </thead>

        @foreach ($articles as $article)
        <tr class="rowArticle{{$article->id}}">
            <td class="colArticleId">{{ $article->id }}</td>
            <td class="colArticleTitle">{{ $article->title }}</td>
            <td class="colArticleDescription">{!! $article->description !!}</td>
            <td class="colArticleTypeTitle">
                {{$article->articleType->title}}
            </td>
            <td>
                <button type="button" class="btn btn-dark show-article" data-article_id='{{$article->id}}'>Show</button>
                <button type="button" class="btn btn-warning update-article" data-article_id='{{$article->id}}'>Update</button>
                <input class="delete-article" type="checkbox"  name="articleDelete[]" value="{{$article->id}}" />
            </td>
        </tr>
        @endforeach
    </table>
    <button class="btn btn-danger" id="delete-selected">Delete Selected</button>
</div>

<div class="modal fade" id="createArticleModal" tabindex="-1" role="dialog" aria-labelledby="createArticleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Article</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="articleAjaxForm">
                <div class="form-group row">
                    <label for="article_title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                    <div class="col-md-6">
                        <input id="article_title" type="text" class="form-control" name="article_title">
                        <span class="invalid-feedback article_title" role="alert"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="article_description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                    <div class="col-md-6">
                        <textarea id="article_description" name="article_description" class="summernote form-control">
                        </textarea>
                        <span class="invalid-feedback article_description" role="alert"></span>
                    </div>
                </div>
                <div class="form-group row article_type_id">
                    <label for="article_type_id" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>

                    <div class="col-md-6">

                        <select id="article_type_id" class="form-control" name="article_type_id">
                            @foreach ($types as $type)
                                <option value="{{$type->id}}"> {{$type->title}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback article_type_id" role="alert"></span>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success addArticleModal">Add</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="showArticleModal" tabindex="-1" role="dialog" aria-labelledby="showArticleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title show-article_title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="show-article_description"></p>
          <p class="show-article_type_id"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="editArticleModal" tabindex="-1" role="dialog" aria-labelledby="editArticleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Article</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="articleAjaxForm">
                <input type='hidden' id='edit-article_id'>
                <div class="form-group row">
                    <label for="article_title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                    <div class="col-md-6">
                        <input id="edit-article_title" type="text" class="form-control" name="article_title">
                        <span class="invalid-feedback article_title" role="alert"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="article_description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                    <div class="col-md-6">
                        <textarea id="edit-article_description" name="article_description" class="summernote form-control">
                        </textarea>
                        <span class="invalid-feedback article_description" role="alert"></span>
                    </div>
                </div>
                <div class="form-group row article_type_id">
                    <label for="article_type_id" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>
                    <div class="col-md-6">
                        <select id="edit-article_type_id" class="form-control" name="article_type_id">
                            @foreach ($types as $type)
                                <option value="{{$type->id}}"> {{$type->title}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback article_type_id" role="alert"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary updateArticleModal">Update</button>
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

    function createTable(articles){
        $(".articles tbody").html("");
        // $(".articles tbody").append("<tr><th>ID</th><th>Title</th><th>Description</th><th>Type</th><th>Actions</th></tr>");
        $.each(articles, function(key, article){
            var articleRow =  "<tr class='rowArticle"+ article.id +"'>";
                articleRow +=  "<td class='colArticleId'>"+ article.id +"</td>";
                articleRow +=  "<td class='colArticleTitle'>"+ article.title +"</td>";
                articleRow +=  "<td class='colArticleDescription'>"+ article.description +"</td>";
                articleRow +=  "<td class='colArticleTypeTitle'>"+ article.type_title +"</td>";
                articleRow += "<td>";
                articleRow += '<button type="button" class="btn btn-dark show-article" data-article_id="'+ article.id +'">Show</button>';
                articleRow += '<button type="button" class="btn btn-warning update-article" data-article_id="'+ article.id +'">Update</button>';
                articleRow += '<input class="delete-article" type="checkbox"  name="articleDelete[]" value="{{$article->id}}"/>';  // patikrinti
                articleRow += "</td>";
                articleRow += "</tr>";
            $(".articles tbody").append(articleRow);
        });
    }

 $(document).ready(function() {
    // $(".addArticleModal").click(function() {
        $(document).on('click', '.addArticleModal', function() {
        var article_title = $("#article_title").val();
        var article_description = $("#article_description").val();
        var article_type_id = $("#article_type_id").val();

        $.ajax({
                type: 'POST',
                url: '{{route("article.storeAjax")}}',
                data: {article_title:article_title, article_description:article_description,article_type_id:article_type_id},
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#createArticleModal").modal("hide");
                        var articleRow =  "<tr class='article rowArticle"+ data.article_id +"'>";
                            articleRow +=  "<td class='colArticleId'>"+ data.article_id +"</td>";
                            articleRow +=  "<td class='colArticleTitle'>"+ data.article_title +"</td>";
                            articleRow +=  "<td class='colArticleDescription'>"+ data.article_description +"</td>";
                            articleRow +=  "<td class='colArticleTypeTitle'>"+ data.article_type_id +"</td>";
                            articleRow += "<td>";
                            articleRow += '<button type="button" class="btn btn-dark show-article" data-article_id="'+ data.article_id +'">Show</button>';
                            articleRow += '<button type="button" class="btn btn-warning update-article" data-article_id="'+ data.article_id +'">Update</button>';
                            articleRow += '<input class="delete-article" type="checkbox"  name="articleDelete[]" value="{{$article->id}}"/>';  // patikrinti
                            articleRow += "</td>";
                            articleRow += "</tr>";
                        $(".articles tbody").append(articleRow);
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div>");
                        $("#article_title").val('');
                        $("#article_description").val('');
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
    $(document).on('click', '.show-article', function() {
       $('#showArticleModal').modal('show');
       var article_id = $(this).attr("data-article_id");
       $.ajax({
                type: 'GET',
                url: '/articles/showAjax/' + article_id ,
                success: function(data) {
                    $('.show-article_title').html('');
                    $('.show-article_description').html('');
                    $('.show-article_type_id').html('');
                    $('.show-article_title').append(data.article_id + '. ' + data.article_title);
                    $('.show-article_description').append(data.article_description);
                    $('.show-article_type_id').append(data.article_type_id);
                }
            });
       console.log(article_id);
    });

    // $(".update-article").click(function() {
        $(document).on('click', '.update-article', function() {

        var article_id = $(this).attr('data-article_id');
        $("#editArticleModal").modal("show");
        $.ajax({
                type: 'GET',
                url: '/articles/editAjax/' + article_id ,
                success: function(data) {
                    $("#edit-article_id").val(data.article_id);
                  $("#edit-article_title").val(data.article_title);
                  $("#edit-article_description").val(data.article_description);
                  $("#edit-article_type_id").val(data.article_type_id);
                }
            });
    })

    $(".updateArticleModal").click(function() {
        var article_id = $("#edit-article_id").val();
        var article_title = $("#edit-article_title").val();
        var article_description = $("#edit-article_description").val();
        var article_type_id = $("#edit-article_type_id").val();
        $.ajax({
                type: 'POST',
                url: '/articles/updateAjax/' + article_id ,
                data: {article_title:article_title, article_description:article_description, article_type_id:article_type_id},
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#editArticleModal").modal("hide");
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div");

                            $(".rowArticle" + article_id + " .colArticleTitle").html(data.article_title);
                            $(".rowArticle" + article_id +" .colArticleDescription").html(data.article_description);
                            $(".rowArticle" + article_id +" .colArticleTypeTitle").html(data.article_type_id);

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
                url: '/articles/searchAjax/',
                data: {searchField: searchField },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        console.log(data.success);
                        $(".articles").css("display", "block");
                        $(".search-alert").html("");
                        $(".search-alert").html(data.success);
                        createTable(data.articles);
                    } else {
                        $(".articles").css("display", "none");
                        $(".articles tbody").html("");
                        $(".search-alert").html("");
                        $(".search-alert").append(data.error);
                    }
                }
            });
        }
    })
    $("#delete-selected").click(function() {
        var checkedArticles = [];
        $.each( $(".delete-article:checked"), function( key, article) {
            checkedArticles[key] = article.value;
        });
        console.log(checkedArticles);
            $.ajax({
                type: 'POST',
                url: '{{route("article.destroySelected")}}',
                data: { checkedArticles: checkedArticles },
                success: function(data) {
                        $(".alerts").toggleClass("d-none");
                        for(var i=0; i<data.messages.length; i++) {
                            $(".alerts").append("<div class='alert alert-"+data.errorsuccess[i] + "'><p>"+ data.messages[i] + "</p></div>")
                            var id = data.success[i];
                            if(data.errorsuccess[i] == "success") {
                                $(".article"+id ).remove();

                            }
                        }

                    }
                });
            })

        $(".delete-article").click(function(){
            var article_id = $(this).val();

    })


    $(document).on('click', '#filterArticles', function() {
        var sortCol = $("#sortCol").val();
        var sortOrder = $("#sortOrder").val();
        var type_id = $("#type_id").val();
        $.ajax({
                type: 'GET',
                url: '/articles/indexAjax/',
                data: {sortCol: sortCol, sortOrder: sortOrder, type_id: type_id },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        createTable(data.articles);
                    } else {
                        console.log(data.error)
                    }
                }
            });
    });


 });



</script>

@endsection
