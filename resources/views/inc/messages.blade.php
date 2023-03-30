@if(count($errors) > 0)
@foreach($errors->all() as $error)
<div class="alert alert-danger" style="">
    {!!$error!!}
</div>
@endforeach
@endif

@if(session('success'))
<div class="alert alert-success text-center" style="">
    {!!session('success')!!}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger text-center" style="">
    {!!session('error')!!}
</div>
@endif

@if(session('notification'))
<div class="alert alert-primary text-center" style="">
    {!!session('notification')!!}
</div>
@endif

@if(session('warning'))
<div class="alert alert-warning text-center" style="">
    {!!session('warning')!!}
</div>
@endif