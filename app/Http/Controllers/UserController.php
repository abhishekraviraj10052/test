<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;

class UserController extends Controller
{
    public function index(Request $request){

        $currentUser=\Auth::user();

        if($currentUser['role'] == 2){
            $query=DB::table('users');
        }else if($currentUser['role'] == 1){
            $query=DB::table('users')->where('admin_id',$currentUser['id']);  
        }else if($currentUser['role'] == 0){
            $query=DB::table('users')->where('id',$currentUser['id']);
        }

                $order_by=$request['order_by'] ?? 'firstname';
                $order=$request['order'] ?? 'asc';
                if($request['firstname'] != ''){
                    $query->where('firstname','like','%'.$request['firstname'].'%');
                }
                if($request['lastname'] != ''){
                    $query->where('lastname','like','%'.$request['lastname'].'%');
                }
                if($request['email'] != ''){
                    $query->where('email','like','%'.$request['email'].'%');
                }
                if($request['number'] != ''){
                    $query->where('number','like','%'.$request['number'].'%');
                }
                $users=$query->orderBy($order_by,$order)->simplePaginate(4)->withQueryString();
                return view('users',compact('users'));
       
    }


    public function login(Request $request){
        $credentials=$request->only('email','password');
        if(\Auth::attempt($credentials)){
            return redirect()->route('user_details');
        }else{
            return back()->with('error','Invalid credentials')->withInput();
        }
    }

    public function user_view(Request $request){
        // return $request->all();
        $currentUser=\Auth::user();
        $admins=DB::table('users')->where('role',1)->get(); 
        if($request['id'] != ''){
            if($currentUser['role']==2){
                $user=DB::table('users')->where('id',$request['id'])->first(); 
                if($user){
                    if($request['admin_id'] != ''){
                        $admin_id=$request['admin_id'];
                        return view('user',compact('user','admins','admin_id'));
                    }
                    return view('user',compact('user','admins'));
                }else{
                    'No user found';
                }
            }
            else if($currentUser['role']==1){
                $user=DB::table('users')->where('id',$request['id'])->first(); 
                if($user->admin_id == $currentUser['id']){
                    return view('user',compact('user','admins'));
                }else{
                    'access denied';
                }
            
            }else if($currentUser['role']==0){
                if($request['id'] == $currentUser['id']){
                    $user=DB::table('users')->where('id',$request['id'])->first(); 
                }else{
                    return 'access denied';
                }
                return view('user',compact('user'));
            }
        }else{
                    $admin_id=$request['admin_id'] ?? '';
                    return view('user',compact('admins','admins','admin_id'));
        }
       
    }

    public function manage_user(Request $request){
        $currentUser=\Auth::user();
        $role='';
        $admin_id='';
        if($currentUser['role'] == 2){
            if($request['role'] != ''){
                $role=$request['role'];
                
            }
            if($request['admin_id'] != ''){
                $admin_id=$request['admin_id'];
            }
        }else if($currentUser['role'] == 1){
            $role=0;
            $admin_id=$currentUser['id'];
        }
        $success = '';

        if($request['id'] != ''){
            if($request['password'] != ''){
                $user=array(
                    'firstname'=>$request['firstname'],
                    'lastname'=>$request['lastname'],
                    'email'=>$request['email'],
                    'number'=>$request['number'],
                    'password'=>\Hash::make($request['password'])
                );
            }else{
                $user=array(
                    'firstname'=>$request['firstname'],
                    'lastname'=>$request['lastname'],
                    'email'=>$request['email'],
                    'number'=>$request['number']
                );
            }
            //image processing code start
            if($request->hasFile('image')){
                $olduser=DB::table('users')->where('id',$request['id'])->first();
                $filename=rand().'-'.$request->file('image')->getClientOriginalName();
                $request->file('image')->move(public_path().'/uploads/images/',$filename);
                $user['image']=$filename;
                if($olduser->image != '' && file_exists(public_path().'/uploads/images/'.$olduser->image)){
                    unlink(public_path().'/uploads/images/'.$olduser->image);
                }
            }
            if($currentUser['role'] == 1 || $currentUser['role'] == 2){
                if($role != ''){
                    $user['role']=$role;
                }
               if($admin_id != ''){
                    $user['admin_id']=$admin_id;
               }
            }
             //image processing code start
            DB::table('users')->where('id',$request['id'])->update(
                $user
            ); 
            $success = 'User updated';
        }else{


             //image processing code start
             if($request->hasFile('image')){
                $filename=rand().'-'.$request->file('image')->getClientOriginalName();
                $request->file('image')->move(public_path().'/uploads/images/',$filename);
             }
            //image processing code start

            DB::table('users')->insert([
                'firstname'=>$request['firstname'],
                'lastname'=>$request['lastname'],
                'email'=>$request['email'],
                'number'=>$request['number'],
                'image'=>$filename ?? NULL,
                'admin_id'=>$currentUser['id'],
                'role'=>0,
                'status'=>0,
                'password'=>\Hash::make($request['password'])
            ]); 
    
            $success = 'User created';
        }
       
        return redirect()->route('user_details')->with('success',$success);
        
    }

    public function user_delete(Request $request){
        // return $request['id'];
        $user=DB::table('users')->where('id',$request['id']);
        $image=$user->first()->image;
        if($image != '' && file_exists(public_path().'/uploads/images/'.$image)){
            unlink(public_path().'/uploads/images/'.$image);
        }
        $user->delete();
        $success = 'User deleted';
        return redirect()->route('user_details')->with('success',$success);

    }


    public function logout(Request $request){
       \Session::flush();
       \Auth::logout();
        return redirect('/');
    }


    public function sub_users(Request $request){
        $users=DB::table('users')->where('admin_id',$request['admin_id'])->paginate(4);
        $admin_id=$request['admin_id'];
        return view('sub_users',compact('users','admin_id'));
    }
}
