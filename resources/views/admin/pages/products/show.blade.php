@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="container">
        <div class="formdiv">
            <h1>Product</h1>
            <hr>

            <label for="username"><b>Product Name</b></label>
            <input type="text" placeholder="Enter Product Name" class="read-only-input"
                value="{{ old('name', $product->name) }}" required>

            <label for="username"><b>Category Name</b></label>
            <input type="text" placeholder="Enter Product Name" class="read-only-input"
                value="{{ $product->category->name }}" required>

            <a href="{{ route('admin.product.index') }}" class="btn btn-primary actionbtn">
                Back
            </a>

        </div>
    </div>
@endsection
