@extends('master.master')
@php
  $firstname=$_GET['firstname'] ?? '';
  $lastname=$_GET['lastname'] ?? '';
  $email=$_GET['email'] ?? '';
  $number=$_GET['number'] ?? '';
  $order_by=$_GET['order_by'] ?? 'firstname';
  if(isset($_GET['order'])){
     if($_GET['order'] == 'asc'){
       $order = 'desc';
     }else{
      $order='asc';
     }
  }

@endphp
@section('content')
@if(Auth::user()->role == 2 || Auth::user()->role == 1)
<div class="container mt-5">
    <form id="search_form">
      <div class="d-flex">
        <input type="text" class="form-control mx-2" name="firstname" placeholder="Enter firstname" value="{{Request::get('firstname') ?? ''}}">
        <input type="text" class="form-control mx-2" name="lastname" placeholder="Enter lastname" value="{{Request::get('lastname') ?? ''}}">
        <input type="text" class="form-control mx-2" name="email" placeholder="Enter email" value="{{Request::get('email') ?? ''}}">
        <input type="text" class="form-control mx-2" name="number" placeholder="Enter number" value="{{Request::get('number') ?? ''}}">
        <input type="hidden" name="order_by" value="{{$order_by ?? 'firstname'}}">
        <input type="hidden" name="order" value="{{$_GET['order'] ?? 'asc'}}">
        <button type="submit" class="btn btn-primary mx-2">Search</button>
        <button type="button" class="btn btn-primary mx-2" id="reset">Reset</button>
      </div>
    </form>
</div>
@endif
<div class="container mt-5">
    @if(Session('success'))
    <div class="alert alert-success text-center">{{Session('success')}}</div>
    @endif
  <div class="">
  <h2>Users</h2>
  <a href="{{route('user_manage')}}" class="mx-4">Create New User</a>
  </div>
  <table class="table table-bordered text-center">
    <thead>
      <tr>
        <th>Image</th>
        <th class="sort" data-type="firstname" data-order="{{$order ?? 'asc'}}">Firstname</th>
        <th class="sort" data-type="lastname" data-order="{{$order ?? 'asc'}}">Lastname</th>
        <th class="sort" data-type="email" data-order="{{$order ?? 'asc'}}">Email</th>
        <th class="sort" data-type="number" data-order="{{$order ?? 'asc'}}">Number</th>
        <th>Role</th>
        <th>Status</th>
        <th>View</th>
        <th>Edit</th>
        <th><a href="{{route('user_logout')}}">Log Out</a></th>
      </tr>
    </thead>
    <tbody>
      @if(count($users) > 0)
      @foreach($users as $user)
      <tr>
        @if($user->image)
        <td><img src="{{asset('uploads/images/'.$user->image)}}" alt=""></td>
        @else
        <td><img src="{{asset('uploads/icons/no_image.jpeg')}}" alt=""></td>
        @endif
        <td>{{$user->firstname}}</td>
        <td>{{$user->lastname}}</td>
        <td>{{$user->email}}</td>
        <td>{{$user->number}}</td>
        @if($user->role==0)
        <td>{{'User'}}</td>
        @elseif($user->role==1)
        <td>{{'Admin'}}</td>
        @endif
        @if($user->status==0)
        <td>{{'Inactive'}}</td>
        @elseif($user->status==1)
        <td>{{'Active'}}</td>
        @endif
        <td>
        @if($user->role==1)
          <a href="{{route('sub_users',['admin_id'=>$user->id])}}">Users</a>
        @else
          <a href="{{route('addressess',['id'=>$user->id])}}">Address</a>
        @endif
        </td>
        <td><a href="{{route('user_manage',['id'=>$user->id])}}">Edit</a></td>
        <td><a href="{{route('user_delete',['id'=>$user->id])}}" class="user_delete" data-id="{{$user->id}}">Delete</a></td>
      </tr>
      @endforeach
      @else
     <tr>
        <td colspan="7">No users found</td>
     </tr>
     @endif
    </tbody>
  </table>
  <div class="row">
    {{$users->links()}}
  </div>
</div>



@endsection
@push('script')
<script>
  $(document).ready(function(){

      let firstname='{{$firstname}}'
      let lastname='{{$lastname}}'
      let email='{{$email}}'
      let number='{{$number}}'
      let url="{{url('user/details?')}}"

      $('#reset').click(function(){
        window.location.href="{{url('user/details')}}"
      })

      $('.sort').click(function(){
          let type=$(this).attr('data-type')
          let order=$(this).attr('data-order')
        
          if(firstname != ''){
            url += 'firstname='+firstname+'&'
          }
          if(lastname != ''){
            url += 'lastname='+lastname+'&'
          }
          if(email != ''){
            url += 'email='+email+'&'
          }
          if(number != ''){
            url += 'number='+number+'&'
          }
          url += 'order_by='+type+'&order='+order
            window.location.href=url
      })

      $('#search_form').submit(function(e){
          e.preventDefault()

          let firstname=$("input[name=firstname]").val().trim()
          let lastname=$("input[name=lastname]").val().trim()
          let email=$("input[name=email]").val().trim()
          let number=$("input[name=number]").val().trim()
          let type=$("input[name=order_by]").val().trim()
          let order=$("input[name=order]").val().trim()



          if(firstname != ''){
            url += 'firstname='+firstname+'&'
          }
          if(lastname != ''){
            url += 'lastname='+lastname+'&'
          }
          if(email != ''){
            url += 'email='+email+'&'
          }
          if(number != ''){
            url += 'number='+number+'&'
          }
          url += 'order_by='+type+'&order='+order
            window.location.href=url
      })
      $(document).on('click','.user_delete',function(e){
        e.preventDefault()
        if(confirm('Do you really want to delete this?')){
          window.location.href="{{route('user_delete')}}/"+$(this).attr('data-id')
        }
      })
  })
</script>
@endpush
@push('style')
<style>
 th{
  cursor:pointer
 }
 img{
  width:50px;
  height:50px;
 }
</style>
@endpush