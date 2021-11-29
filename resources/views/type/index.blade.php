@extends('layouts.app')

@section('content')

<div class="container">

    <div class="alerts d-none"></div>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTypeModal">
        Create New Type Modal
    </button>
    <table class="table table-bordered table-hover gray types">
        <thead class="thead-dark">
        <tr>
            <th> ID </th>
            <th> Title </th>
            <th> Description</th>
            <th> Action</th>
        </tr>
        </thead>

        @foreach ($types as $type)
        <tr class="type">
            <td>{{ $type->id }}</td>
            <td><a class="intgray" href="{{route('type.show', [$type])}}">{{ $type->title }}</a></td>
            <td>{!! $type->description !!}</td>
            <td>
                <button type="button" class="btn btn-dark show-type" data-type_id='{{$type->id}}'>Show</button>
                <button type="button" class="btn btn-warning update-type" data-type_id='{{$type->id}}'>Update</button>
                <input class="delete-type" type="checkbox"  name="typeDelete[]" value="{{$type->id}}" />
            </td>
        </tr>
        @endforeach
    </table>

</div>

<div class="modal fade" id="createTypeModal" tabindex="-1" role="dialog" aria-labelledby="createTypeModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Type</h5>
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

<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        }
    });
 $(document).ready(function() {
    $(".addTypeModal").click(function() {
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
                        $(".types").append("<tr><td>"+ data.type_id +"</td><td>"+ data.type_title +"</td><td>"+ data.type_description +"</td><td><button type='button' class='btn btn-dark show-type' data-type_id='"+data.type_id+"'>Show</button><button type='button' class='btn btn-warning update-type' data-type_id='"+data.type_id+"'>Update</button></td></tr>");
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

    $("#delete-selected").click(function() {
        var checkedTypes = [];
        $.each( $(".delete-article:checked"), function( key, type) {
            checkedTypes[key] = type.value;
        });
        console.log(checkedTypes);
            $.ajax({
                type: 'POST',
                url: '{{route("type.destroySelected")}}',
                data: { checkedTypes: checkedTypes },
                success: function(data) {
                        $(".alerts").toggleClass("d-none");
                        for(var i=0; i<data.messages.length; i++) {
                            $(".alerts").append("<div class='alert alert-"+data.errorsuccess[i] + "'><p>"+ data.messages[i] + "</p></div>")
                            var id = data.success[i];
                            if(data.errorsuccess[i] == "success") {
                                $(".type"+id ).remove();
                            }
                        }

                    }
                });
            })

        $(".delete-type").click(function(){
            var type_id = $(this).val();

    })
 });

});



</script>


@endsection
