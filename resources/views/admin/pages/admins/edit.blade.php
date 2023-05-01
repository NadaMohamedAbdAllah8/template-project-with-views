@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="container">
        <div class="formdiv">
            <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
                @csrf {{ method_field('PUT') }}
                <h1>Edit Admin</h1>
                <hr>

                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label for="username"><b>Admin Name</b></label>
                        <input type="text" placeholder="Enter Admin Name" name="name" required
                            value="{{ $admin->name }}">
                    </div>
                    <div class="col-lg-6">
                        <label for="username"><b>Admin Email</b></label>
                        <input type="text" placeholder="Enter Admin Email" name="email" required
                            value="{{ $admin->email }}">
                    </div>
                </div>


                <a href="{{ route('admin.admins.index') }}" class="btn btn-primary actionbtn">
                    Back
                </a>
                <button type="submit" class="actionbtn btn btn-primary">Edit</button>

            </form>
        </div>
    </div>
@endsection
