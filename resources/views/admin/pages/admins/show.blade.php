@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="container">
        <div class="formdiv">

            <h1> Admin</h1>

            <div class="form-group m-form__group row">
                <div class="col-lg-6">
                    <label for="username"><b>Admin Name</b></label>
                    <input type="text" class="read-only-input" name="name" value="{{ $admin->name }}">
                </div>
                <div class="col-lg-6">
                    <label for="username"><b>Admin Email</b></label>
                    <input type="text" class="read-only-input" name="country" value="{{ $admin->email }}">
                </div>
            </div>

            <a href="{{ route('admin.admins.index') }}" class="btn btn-primary actionbtn">
                Back
            </a>
        </div>
    </div>
@endsection
