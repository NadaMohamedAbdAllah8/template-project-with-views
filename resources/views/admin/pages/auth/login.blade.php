@extends('layouts.master')
@section('title')
    {{ $title ?? 'Login-Admin' }}
@endsection

@section('content')
    <div class="formdiv">
        <form action="{{ route('admin.login-post') }}" method="POST">
            @csrf
            <h1>Login</h1>
            <hr>

            <label for="username"><b>Email</b></label>
            <input type="email" placeholder="Enter Admin Email" name="email" id="username" required>


            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" id="psw" required>

            <button type="submit" class="actionbtn primary">Sign in</button>
        </form>
    </div>
@endsection
