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
use Mail;

class PPUKMUserController extends Controller 
{
public $successStatus = 200;
/** 
     * login PPUKM 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){
         
        $validator = Validator::make($request->all(), [ 
            'email'=>'required' , 
            'password'=>'required',
         ]);

         $email= $request->input('email');
         $password = $request->input('password');

         if ($validator->fails()) {
            if (($email == "") && ($password == "")){
                return response()->json(['error'=> TRUE, 'error_message'=>"Please enter the email and password"]); 
            }else{
                if ($email == ""){
                    return response()->json(['error'=> TRUE, 'error_message'=>"Please enter the email"]); 
                }
                else{
                        return response()->json(['error'=> TRUE, 'error_message'=>"Please enter the password"]); 
                }
            }            
                
            //return response()->json(['error'=> TRUE, 'error_message'=>$validator->errors()], 401);            
        }else {

            
           

            $data = User::where('email',  $email)->count(); 

           
           

            if($data > 0) {
                $ppukmdata = User::where('email',  $email)->first();
                $emailstatus = $ppukmdata->is_active;
                $passworddata = $ppukmdata->password;

                if($emailstatus == 0){
                    return response()->json(['error'=> TRUE, 'error_message'=>"Email not active. Please check your email for the verification code"]); 
                }else{
                    if (Auth::attempt(['email' => $email, 'password' => $password, 'typeuser' => 2, 'is_active' => 1])) {
                        $user = Auth::user();
                        $id =  $user->id;
                        $data = PPUKM::where('user_id',  $id)->first(); 
    
                     
                        $token =  $user->createToken('MyApp')-> accessToken;
                        $success['name'] =  $user->name;
                        $success['email'] =  $user->email;
                        $success['phonenumber'] =  $data->ppukm_phonenumber;
                        return response()->json(['error'=> FALSE, 'token'=>$token,'success' => $success], $this-> successStatus); 
                    } else{
                        return response()->json(['error'=> TRUE, 'error_message'=>"Password wrong!"]);

                    }     

                }

            }else {
                return response()->json(['error'=> TRUE, 'error_message'=>"Email not found!Please register first"]); 
            }


           
        }
    }
/** 
     * Register PPUKM
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
           'name'=>'required' ,
           'email'=>'required|email|max:255|unique:users',
           'phonenumber'=>'required', 
           'password'=>'required|min:5', 
           'confirm_password' => 'required|same:password',  
        ]);




        if ($validator->fails()) {
            return response()->json(['error'=> TRUE, 'error_message'=>$validator->errors()], 401);            
        }else {
                    $name = $request->input('name');
                    $email= $request->input('email');
                    $phonenumber = $request->input('phonenumber');
                    $password = $request->input('password');

                    $dataEmail = User::where('email',  $email)->count();

                     if($dataEmail > 0){
                        return response()->json(['error'=> TRUE, 'error_message'=>"Email already exist"]);
                    }else{

                    $rand = $this->generateRandomString(8);

                    $dataUser = new \App\User();
                    $dataUser->name = $name;
                    $dataUser->email = $email;
                    $dataUser->password = bcrypt($password);
                    $dataUser->typeuser = 2;
                    $dataUser->email_verified_code = $rand;
                    $dataUser->is_active = 1;

                if($dataUser->save()){
                            $id =  $dataUser->id;
                            $data = new \App\PPUKM;
    
                            $data->ppukm_name = $name;
                            $data->ppukm_email = $email;
                            $data->ppukm_phonenumber = $phonenumber;
                            $data->user_id = $id;

                            if($data->save()){
                                        $success['token'] =  $dataUser->createToken('MyApp')-> accessToken; 
                                        $success['name'] =  $dataUser->name;
                                        $success['email'] =  $dataUser->email;
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
     * Generate Random Number
     * 
     * @return \Illuminate\Http\Response 
     */ 


    public function generateRandomString($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
     }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    // public function details() 
    // { 
    //     $user = Auth::user()->id;
    //     $data = DB::select('SELECT * FROM ppukm WHERE user_id = :id', ['id' => $user]);
    //     return response()->json(['success' => $data], $this-> successStatus); 
    // } 

    // public function logout(Request $request)
    // {
    //     $request->user()->token()->revoke();
    //     return response()->json([
    //         'message' => 'Successfully logged out'
    //     ]);
    // }
}
