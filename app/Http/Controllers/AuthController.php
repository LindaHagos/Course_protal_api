<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;//
use Illuminate\Support\Facades\Validator;//


use App\Models\Role;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        /*$validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed|min:2'
        ]);*/
        $validatedData= Validator::make($request->all(), [
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
          //  'password' => 'required|min:2'
            'password' => 'required|confirmed|min:2'
        ]);

        if($validatedData->fails()){
            return response(['message' => $validatedData->errors(),422]);
        }
        $data =[
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
        ];
       // $validatedData['password'] = bcrypt($request->password);
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);
       // $user = User::create($validatedData);
       $role = new Role();
       $role->role = $request->role;

       $user->role()->save($role);

        //$accessToken = $user->createToken('authToken')->accessToken;
        $accessToken = $user->createToken('authToken', [$request->role])->accessToken;

        return response([ 'user' => new UserResource($user), 'access_token' => $accessToken],200);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }
        $user = auth()->user();

        //when ever you want to assign role
        $userRole = $user->role()->first();

        if ($userRole) {
            $this->scope = $userRole->role;
        }
        $accessToken = $user->createToken('authToken', [$this->scope])->accessToken;

       // $accessToken = auth()->user()->createToken('authToken')->accessToken;

        //return response(['user' => auth()->user(), 'access_token' => $accessToken]);
        return response(
            [
                'user' => new  UserResource($user),
                'access_token' => $accessToken
            ],200);

    }
}
