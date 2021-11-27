@extends('layouts.app')

@section('content')

<div class="container">
    <table class="table table-bordered table-hover gray">
        <thead class="thead-dark">
        <tr>
            <th> ID </th>
            <th> Title </th>
            <th> Description</th>
            <th> Action</th>
        </tr>
        </thead>

        @foreach ($types as $type)
        <tr>
            <td>{{ $type->id }}</td>
            <td><a class="intgray" href="{{route('type.show', [$type])}}">{{ $type->title }}</a></td>
            <td>{!! $type->description !!}</td>
            <td>
                Action
            </td>
        </tr>
        @endforeach
    </table>

</div>




@endsection
