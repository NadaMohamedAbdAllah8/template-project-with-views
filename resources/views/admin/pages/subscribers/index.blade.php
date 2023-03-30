@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="container">
        <a href="{{ route('admin.subscribers.create') }}" class="btn btn-primary actionbtn">
            Create Subscriber
        </a>
        @if (count($subscribers) == 0)
            <p>No records</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Handle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                    </tr>
                </tbody>
            </table>
    </div>
    @endif
@endsection
