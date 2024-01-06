@extends('master.master')

@section('content')
<div class="container mt-5">
    @if(isset($admin_id) && $admin_id != '')
    <a href="{{route('sub_users',$admin_id)}}">Back</a>
    @else
    <a href="{{route('user_details')}}">Back</a>
    @endif
    @if(Session('error'))
    <div class="alert alert-danger text-center">{{Session('error')}}</div>
    @endif
    <form action="{{route('user_manage')}}" method="POST" enctype="multipart/form-data">
        @method('post')
        @csrf
        <div class="mb-3">
            <input type="hidden" name="id" value="{{$user->id ?? ''}}">
            <input type="text" class="form-control" name="firstname" placeholder="Enter firstname" value="{{$user->firstname ?? ''}}">
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" name="lastname" placeholder="Enter lastname" value="{{$user->lastname ?? ''}}">
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" name="email" placeholder="Enter email" value="{{$user->email ?? ''}}">
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" name="number" placeholder="Enter number" value="{{$user->number ?? ''}}">
        </div>
        <div class="mb-3">
            <input type="file" class="form-control" name="image">
        </div>
        @if(Auth::user()->role == 2)
        <div class="mb-3">
          <select class="form-control role" name="role">
            <option value="">Select Role</option>
            <option value="1" @if(isset($user) && $user->role==1) selected  @endif>Admin</option>
            <option value="0" @if(isset($user) && $user->role==0) selected  @endif>User</option>
          </select>        
        </div>
        @endif
        <div class="mb-3">
          <select  class="form-control admin_id" name="admin_id">
            <option value="">Assign Admin</option>
            @foreach($admins as $admin)
            <option value="{{$admin->id}}">{{$admin->firstname}}</option>
            @endforeach
          </select>        
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Enter password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection

@push('script')
<script>
 $(document).ready(function(){
    $('.role').change(function(){
        if($(this).val().trim() != '' && $(this).val().trim() == 0){
            $('.admin_id').show()
        }else{
            $('.admin_id').val('')
            $('.admin_id').hide()
        }
    })
 })
</script>
@endpush
@push('style')
<style>
 .admin_id{
  display:none;
 }
</style>
@endpush