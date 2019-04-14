<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\Kin;
use App\Patient;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Facades\Hash;
use Mail;


class KinUserController extends Controller 
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
                $kindata = User::where('email',  $email)->first();
                $emailstatus = $kindata->is_active;
                $passworddata = $kindata->password;

                if($emailstatus == 0){
                    return response()->json(['error'=> TRUE, 'error_message'=>"Email not active. Please check your email for the activation code to verify your email.If you don't get email for the activation code"]); 
                }else{
                    if (Auth::attempt(['email' => $email, 'password' => $password, 'typeuser' => 3, 'is_active' => 1])) {
                        $user = Auth::user();
                        $id =  $user->id;
                        $data = Kin::where('user_id',  $id)->first(); 
    
                     
                        $token =  $user->createToken('MyApp')-> accessToken;
                        $success['name'] =  $user->name;
                        $success['email'] =  $user->email;
                        $success['phonenumber'] =  $data->kin_phonenumber;
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
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name'=>'required' , 
            'email'=>'required|email|max:255|unique:users',
            'phonenumber'=>'required', 
            'password'=>'required|min:6', 
            'c_password' => 'required|same:password',  
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
                    $rand = $this->generateRandomString(4);
 
                     $dataUser = new \App\User();
                     $dataUser->name = $name;
                     $dataUser->email = $email;
                     $dataUser->password = bcrypt($password);
                     $dataUser->typeuser = 3;
                     $dataUser->email_verified_code = $rand;
                     $dataUser->is_active = 0;
 
                          if($dataUser->save()){
                            Mail::send('emails.contact-message', [
                                'msg' => $rand
                            ], function ($mail) use ($request){
                                $mail->from('patienttracking23@gmail.com');
                    
                                $mail->to($request->email, $request->name)->subject('Your Verification Code');
                            });
                                 $id =  $dataUser->id;
                                 $data = new \App\Kin;
     
                                 $data->kin_name = $name;
                                 $data->kin_email = $email;
                                 $data->kin_phonenumber = $phonenumber;
                                 $data->user_id = $id;
 
                                     if($data->save()){
                                         $token =  $dataUser->createToken('MyApp')-> accessToken; 
                                         $success['name'] =  $dataUser->name;
                                         $success['email'] =  $dataUser->email;
                                         $success['phonenumber'] =  $data->kin_phonenumber;
 
                                         return response()->json(['error'=> FALSE,'token'=>$token,'success'=>$success], $this-> successStatus); 
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
     * Generate random string
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
     * Email Activation 
     * 
     * @return \Illuminate\Http\Response 
     */ 

    public function activation(Request $request){

        $validator = Validator::make($request->all(), [ 
            'email_verified_code'=>'required' 
         ]);

         
         if ($validator->fails()) {
            return response()->json(['error'=> TRUE, 'error_message'=>$validator->errors()], 401);            
        }else {

            $code = $request->input('email_verified_code');

            $user = User::where('email_verified_code',  $code)->count();
          
            if($user > 0)
            {
                $usID = User::where('email_verified_code',  $code)->first();
                $ID = $usID->id;

                DB::table('users')
                ->where('id', $ID)
                ->update(['is_active' => 1]);

              return response()->json(['error'=> FALSE, 'success' => "Email has been verify. Thank You"], $this-> successStatus);     
            }
  
            else{
              
                  return response()->json(['error'=> TRUE, 'message' => 'Email verification fail!, Please register' ],404);
                
            }
            return response()->json(['error'=> TRUE, 'error_message' => 'Internal Server Error' ],500);
          }
    }

     /** 
     * Request Code 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function requestCode(Request $request){

        $validator = Validator::make($request->all(), [ 
            'email'=>'required' 
         ]);

         
         if ($validator->fails()) {
            return response()->json(['error'=> TRUE, 'error_message'=>$validator->errors()], 401);            
        }else {

            $email = $request->input('email');

            $userEmail = User::where('email',  $email)->count();
          
            if($userEmail > 0)
            {
                $usID = User::where('email',  $email)->first();
                $ID = $usID->id;

                $rand = $this->generateRandomString(4);

                DB::table('users')
                ->where('id', $ID)
                ->update(['email_verified_code' => $rand]);

                Mail::send('emails.contact-message', [
                    'msg' => $rand
                ], function ($mail) use ($request){
                    $mail->from('patienttracking23@gmail.com');
        
                    $mail->to($request->email, $request->name)->subject('Your Verification Code');
                });

              return response()->json(['error'=> FALSE, 'success' => "Email has been verify. Thank You"], $this-> successStatus);     
            }
  
            else{
              
                  return response()->json(['error'=> TRUE, 'message' => 'Email not found!, Please register' ],404);
                
            }
            return response()->json(['error'=> TRUE, 'error_message' => 'Internal Server Error' ],500);
          }
    }

     /** 
     * Create Relationship
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function createRelationship(Request $request){

        $validator = Validator::make($request->all(), [
            'barcode'=>'required'  
            
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }else {

          $barcode = $request->input('barcode');

            
          $patient = Patient::where('barcode',  $barcode)->count();
          

          if($patient > 0)
          {
            $user = Auth::user();
            $userid = $user->id;
            $kin = Kin::where('user_id',  $userid)->first();
            $kinid = $kin->kin_id;

            $dataPatient = Patient::where('barcode', $barcode)->first();
            $patientid = $dataPatient->patient_id;
            
            Patient::where('patient_id', $patientid)->update(array(
                    'kin_id'=>$kinid,

                ));
            return response()->json(['error'=> FALSE, 'success' => "Relationship created. Thank You"], $this-> successStatus);     
          }

          else{
            
                return response()->json(['error'=> TRUE, 'message' => 'Patient not found' ],404);
              
          }
          return response()->json(['error'=> TRUE, 'error_message' => 'Internal Server Error' ],500);
        }
      }
        

}