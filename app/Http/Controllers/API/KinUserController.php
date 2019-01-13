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

         if ($validator->fails()) {
            return response()->json(['error'=> TRUE, 'error_message'=>$validator->errors()], 401);            
        }else {
            if(Auth::attempt(['email' => request('email'), 'password' => request('password'), 'typeuser' => 3])){ 
                $user = Auth::user();
                $id =  $user->id;
                $data = Kin::where('user_id',  $id)->first(); 

                $emaildata = $user->email;
                $passworddata = $user->password;
              

                $email= $request->input('email');
                $password = $request->input('password');

                        if($emaildata != $email){
                            return response()->json(['error'=> TRUE, 'error_message'=>"Email not found"]); 

                        }else{
                            if(Hash::check($password, $passworddata)){
                                $success['token'] =  $user->createToken('MyApp')-> accessToken;
                                $success['name'] =  $user->name;
                                $success['email'] =  $user->email;
                                $success['icnumber'] =  $data->kin_icnumber;
                                $success['phonenumber'] =  $data->kin_phonenumber;
                                return response()->json(['error'=> FALSE, 'success' => $success], $this-> successStatus); 
                               
                            }else{
                                return response()->json(['error'=> TRUE, 'error_message'=>"Password wrong"]);
                               
                            } 
                        }
                       
                    }
             else{ 
                return response()->json(['error'=> TRUE, 'error_message'=>"Password wrong"]);
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
            'icnumber'=>'required', 
            'email'=>'required|email|max:255|unique:users',
            'phonenumber'=>'required', 
            'password'=>'required|min:6', 
            'c_password' => 'required|same:password',  
         ]);
 
 
 
 
         if ($validator->fails()) {
             return response()->json(['error'=> TRUE, 'error_message'=>$validator->errors()], 401);            
         }else {
                     $name = $request->input('name');
                     $icnumber= $request->input('icnumber');
                     $email= $request->input('email');
                     $phonenumber = $request->input('phonenumber');
                     $password = $request->input('password');
 
                     $dataICID = Kin::where('kin_icnumber',  $icnumber)->count();
                     $dataEmail = User::where('email',  $email)->count();
 
                     if($dataICID > 0){
                         return response()->json(['error'=> TRUE, 'error_message'=>"I/C Number already exist"]); 
                     }elseif($dataEmail > 0){
                         return response()->json(['error'=> TRUE, 'error_message'=>"Email already exist"]);
                     }else{
 
                     $dataUser = new \App\User();
                     $dataUser->name = $name;
                     $dataUser->email = $email;
                     $dataUser->password = bcrypt($password);
                     $dataUser->typeuser = 3;
 
                          if($dataUser->save()){
         
                                  $id =  $dataUser->id;
                 
                                  $data = new \App\Kin;
     
                                 $data->kin_name = $name;
                                 $data->kin_icnumber = $icnumber;
                                 $data->kin_email = $email;
                                 $data->kin_phonenumber = $phonenumber;
                                 $data->user_id = $id;
 
                                     if($data->save()){
                                         $token['token'] =  $dataUser->createToken('MyApp')-> accessToken; 
                                         $success['name'] =  $dataUser->name;
                                         $success['email'] =  $dataUser->email;
                                         $success['icnumber'] =  $data->kin_icnumber;
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
     * details api 
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