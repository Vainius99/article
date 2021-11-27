@extends('layouts.app')

@section('content')

<div class="container">

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createArticleModal">
        Create New Client Modal
    </button>

    <table class="table table-bordered table-hover gray">
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
        <tr>
            <td>{{ $article->id }}</td>
            <td>{{ $article->title }}</td>
            <td>{!! $article->description !!}</td>
            <td>
                {{$article->articleType->title}}
            </td>
            <td>
                <button type="button" class="btn btn-dark show-article" data-article_id='{{$article->id}}'>Show</button>
                <button type="button" class="btn btn-warning update-article" data-article_id='{{$article->id}}'>Update</button>
            </td>
        </tr>
        @endforeach
    </table>

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
 $(document).ready(function() {
    $(".addArticleModal").click(function() {
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
                        $(".articles").append("<tr><td>"+ data.article_id +"</td><td>"+ data.article_title +"</td><td>"+ data.article_description +"</td><td>"+ data.article_type_id +"</td><td>Actions</td></tr>");
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

    $(".show-article").click(function() {
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

    $(".update-article").click(function() {
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
 });



</script>

@endsection
