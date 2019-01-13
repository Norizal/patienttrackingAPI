<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\PPUKM;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\Hash;
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
    public function login(Request $request){

        $validator = Validator::make($request->all(), [ 
            'email'=>'required' , 
            'password'=>'required',
         ]);

         if ($validator->fails()) {
            return response()->json(['error'=> TRUE, 'error_message'=>$validator->errors()], 401);            
        }else {
            $email= $request->input('email');

            $emaildata = User::where('email', $email)->count();

            if($emaildata< 1){
                return response()->json(['error'=> TRUE, 'error_message'=>"Email not found"]); 

            }else{
                if(Auth::attempt(['email' => request('email'), 'password' => request('password'), 'typeuser' => 2])){ 
                    $user = Auth::user();
                    $id =  $user->id;
                    $data = PPUKM::where('user_id',  $id)->first(); 
                
                        $success['token'] =  $user->createToken('MyApp')-> accessToken;
                        $success['name'] =  $user->name;
                        $success['email'] =  $user->email;
                        $success['staffid'] =  $data->ppukm_staffid;
                        $success['phonenumber'] =  $data->ppukm_phonenumber;
                        return response()->json(['error'=> FALSE, 'success' => $success], $this-> successStatus); 
                                   
                    
                    }else{
                        return response()->json(['error'=> TRUE, 'error_message'=>"Password wrong"]);
                    }

                    return response()->json(['error'=> TRUE, 'error_message' => 'Internal Server Error' ]);



            }



            return response()->json(['error'=> TRUE, 'error_message' => 'Internal Server Error' ],500);
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
           'name'=>'required' ,
           'staffid'=>'required', 
           'email'=>'required|email|max:255|unique:users',
           'phonenumber'=>'required', 
           'password'=>'required|min:6', 
           'c_password' => 'required|same:password',  
        ]);




        if ($validator->fails()) {
            return response()->json(['error'=> TRUE, 'error_message'=>$validator->errors()], 401);            
        }else {
                    $name = $request->input('name');
                    $staffid = $request->input('staffid');
                    $email= $request->input('email');
                    $phonenumber = $request->input('phonenumber');
                    $password = $request->input('password');

                    $dataStaffID = PPUKM::where('ppukm_staffid',  $staffid)->count();
                    $dataEmail = User::where('email',  $email)->count();

                    if($dataStaffID > 0){
                        return response()->json(['error'=> TRUE, 'error_message'=>"Staff ID already exist"]); 
                    }elseif($dataEmail > 0){
                        return response()->json(['error'=> TRUE, 'error_message'=>"Email already exist"]);
                    }else{

                    $dataUser = new \App\User();
                    $dataUser->name = $name;
                    $dataUser->email = $email;
                    $dataUser->password = bcrypt($password);
                    $dataUser->typeuser = 2;

                         if($dataUser->save()){
        
                                 $id =  $dataUser->id;
                
                                 $data = new \App\PPUKM;
    
                                $data->ppukm_name = $name;
                                $data->ppukm_staffid = $staffid;
                                $data->ppukm_email = $email;
                                $data->ppukm_phonenumber = $phonenumber;
                                $data->user_id = $id;

                                    if($data->save()){
                                        $success['token'] =  $dataUser->createToken('MyApp')-> accessToken; 
                                        $success['name'] =  $dataUser->name;
                                        $success['email'] =  $dataUser->email;
                                        $success['staffid'] =  $data->ppukm_staffid;
                                        $success['phonenumber'] =  $data->ppukm_phonenumber;

                                        return response()->json(['error'=> FALSE,'success'=>$success], $this-> successStatus); 
                                    }
                                    else{
                                    return response()->json(['error'=> TRUE, 'error_message' => 'Internal Server Error' ],500);
                                    }

                             } else {
                                return response()->json(['error'=> TRUE, 'error_message' => 'Internal Server Error' ],500);
                            }

            } 
            return response()->json(['error'=> TRUE, 'error_message' => 'Internal Server Error' ],500);

        }
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