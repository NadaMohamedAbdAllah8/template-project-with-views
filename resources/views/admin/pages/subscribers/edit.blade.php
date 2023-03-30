@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
{{ $title }}
@endsection

@section('content')
<div class="container">
    <div class="formdiv">
        <form action="{{route('admin.category.update',$category->id)}}" method="POST">
            @csrf {{ method_field('PUT') }}
            <h1>Edit Category</h1>
            <hr>

            <label for="username"><b>Category Name</b></label>
            <input type="text" placeholder="Enter Category Name" name="name" value="{{old('name',$category->name)}}"
                required>

            <a href="{{ route('admin.category.index')}}" class="btn btn-primary actionbtn">
                Back
            </a>
            <button type="submit" class="actionbtn btn btn-primary">Edit</button>

        </form>
    </div>
</div>

@endsection