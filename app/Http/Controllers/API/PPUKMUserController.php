<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\PPUKM;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;

class PPUKMUserController extends Controller 
{
public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['id'] =  $user->id;
            $success['name'] =  $user->name;
            return response()->json(['error'=> FALSE, 'success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            $name = $request->input('name') ,
            $staffid = $request->input('staffid'), 
            $email= $request->input('email'),
            $gender = $request->input('gender'),
            $race = $request->input('race'),
            $phonenumber = $request->input('phonenumber'), 
            $password = $request->input('password'), 
            'c_password' => 'required|same:password',  
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }else {

        $data = new \App\PPUKM();
        $data->name = $name;
        $data->staffid = $staffid;
        $data->email = $email;
        $data->gender = $gender;
        $data->race = $race;
        $data->phonenumber = $phonenumber;
        $data->password = bcrypt($password);

        if($data->save()){
            
            $dataUser = new \App\User();
            $dataUser->name = $name;
            $dataUser->email = $email;
            $dataUser->password = bcrypt($password);
            $dataUser->typeuser = 1;

            if($dataUser->save()){
                $success['token'] =  $dataUser->createToken('MyApp')-> accessToken; 
                $success['name'] =  $dataUser->name;
            }
            else{
              return response()->json(['status' => 'error', 'message' => 'Internal Server Error' ],500);
            }
            
          } else {
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error' ],500);
          }


        }
       
        // $input = $request->all(); 
        // $input['password'] = bcrypt($input['password']); 
        // $userPPUKM = DB::insert('insert into ppukm (name, staffid, email, gender, race, phonenumber, password) values (?, ?, ?, ?, ?, ?, ?)', [$name, $staffid, $email, $gender, $race, $phonenumber, $password]);
        // $user = DB::insert('insert into users (id, name, email, password, typeuser, created_at, updated_at) values (?, ?,?, ?, ?, ?)', [$name, $email, $password, 1]);
        // $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        // $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this-> successStatus); 
    }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user()->id;
        $data = DB::select('SELECT * FROM ppukm WHERE user_id = :id', ['id' => $user]);
        return response()->json(['success' => $data], $this-> successStatus); 
    } 

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}