<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'address' => 'required',
            'place' => 'required',
            'country' => 'required',
            'postalcode' => 'required',
            'isAdmin' => 'required'
        ]);

        if($validator->fails()){
            return 'failed';
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        if($user->isAdmin == 1){
            $success['token'] =  $user->createToken('MyApp', ['website'])->plainTextToken;
        }
        else {
            $success['token'] =  $user->createToken('MyApp', ['weather', 'userinfo'])->plainTextToken;
        }
        $success['first_name'] =  $user->first_name;

        return response()->json($success,200);
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $user->tokens()->delete();
            if($user->isAdmin == 1){
                $success['token'] =  $user->createToken('MyApp', ['website'])->plainTextToken;
            }
            else {
                $success['token'] =  $user->createToken('MyApp', ['weather', 'userinfo'])->plainTextToken;
            }
            $success['first_name'] =  $user->first_name;
            $success['last_name'] =  $user->last_name;
            $success['user_id'] =  $user->id;

            return response()->json($success, 200);
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
}
