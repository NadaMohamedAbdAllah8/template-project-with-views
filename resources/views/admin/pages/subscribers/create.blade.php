@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
{{ $title }}
@endsection

@section('content')
<div class="container">
    <div class="formdiv">
        <form action="{{route('admin.category.store')}}" method="POST">
            @csrf
            <h1>Create Category</h1>
            <hr>

            <label for="username"><b>Category Name</b></label>
            <input type="text" placeholder="Enter Category Name" name="name" required>

            <a href="{{ route('admin.category.index')}}" class="btn btn-primary actionbtn">
                Back
            </a>
            <button type="submit" class="actionbtn btn btn-primary">Create</button>

        </form>
    </div>
</div>

@endsection