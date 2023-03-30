@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
{{ $title }}
@endsection

@section('content')
<div class="container">
    <div class="formdiv">
        <form action="{{route('admin.product.store')}}" method="POST">
            @csrf
            <h1>Create Product</h1>
            <hr>

            <label><b>Product Name</b></label>
            <input type="text" placeholder="Enter Product Name" name="name" required>

            <label><b>Category</b></label>
            <select name="category_id">
                <option selected value="">Categories</option>
                @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>

            <a href="{{ route('admin.product.index')}}" class="btn btn-primary actionbtn">
                Back
            </a>
            <button type="submit" class="actionbtn btn-primary primary">Create</button>

        </form>
    </div>
</div>

@endsection