@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
    {{ $title }}
@endsection

@section('content')

    <a href="{{ route('admin.product.create') }}" style="width:20%" class="btn btn-primary actionbtn">
        Create Product
    </a>

    <div class="container">
        <div class="card">
            <div class="card-body">
                @if (count($products) != 0)
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Category</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.product.show', $product->id) }}" title="Show"
                                            class="">
                                            Show</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $products->render() !!}
                    </div>
                @else
                    No records
                @endif
            </div>
        </div>
    </div>

@endsection
