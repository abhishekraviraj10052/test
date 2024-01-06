@extends('master.master')

@section('content')

<div class="container mt-5">
    @if(Session('error'))
    <div class="alert alert-danger text-center">{{Session('error')}}</div>
    @endif
    <form action="{{route('login')}}" method="POST">
        @method('post')
        @csrf
        <div class="mb-3">
            <input type="email" class="form-control" name="email" placeholder="Enter email" value="{{old('email')}}">
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Enter password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection