@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="container">
        <div class="formdiv">
            <form action="{{ route('admin.admins.store') }}" method="POST">
                @csrf
                <h1>Create Admin</h1>
                <hr>
                <div class="form-group m-form__group row">
                    <div class="col-lg-12">
                        <label for="username"><b>Admin Name</b></label>
                        <input type="text" placeholder="Enter Admin Name" name="name" required>
                    </div>

                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label for="username"><b>Admin Email</b></label>
                        <input type="email" placeholder="Enter Admin Email" name="email" required>
                    </div>
                    <div class="col-lg-6">
                        <label for="username"><b>Admin Password</b></label>
                        <input type="password" placeholder="Enter Admin Password" name="password" required>
                    </div>
                </div>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-primary actionbtn">
                    Back
                </a>
                <button type="submit" class="actionbtn btn btn-primary">Create</button>

            </form>
        </div>
    </div>
@endsection
