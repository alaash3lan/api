<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use GuzzleHttp\Client as GuzzleHttp;




class ApiUsersController extends Controller
{


    private $client;

    public function  __construct()
    {
        $this->client = Client::find(2);

    }


    public function create(Request $request ){



       $validator = Validator::make($request->all(),[
           'name' => 'required|max:25',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:6'
       ]);

       if ($validator->fails()){
           return response()->json([
               'success' => false,
               'errors' => $validator->errors()->toArray(),
           ],401);
       }
        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();


       return $this->AccessToken($request ,$user);



   }


   public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ], 401);
        }
        $user = User::where('email', $request->email)->first();
        if(!$user){

            return response(['message' => 'email uncorrect', ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {

            return response(['message' => 'password uncorrect',],401);

        } else {

        return $this->AccessToken($request, $user);


        }


   }


   public function AccessToken($request,$user ){


        $http = new GuzzleHttp;

        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ],
        ]);
        return response(['auth' => json_decode((string)$response->getBody(), true), 'user' => $user]);
   }

}
