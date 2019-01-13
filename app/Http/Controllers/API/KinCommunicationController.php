<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\Kin;
use App\Patient;
use App\KinChat;
use App\PPUKMChat;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;

class KinCommunicationController extends Controller
{
    public $successStatus = 200;

    public function createChat(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'message'=>'required' ,
            

        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=> FALSE,'error_message'=>$validator->errors()], 401);            
        }else {

            $user = Auth::user();
            $userid = $user->id;
            $kin = Kin::where('user_id',  $userid)->first();
            $kinid = $kin->kin_id;
            $patient = Patient::where('kin_id',  $kinid)->first();
            $patientid = $patient->patient_id;
            
            $message= $request->input('message');


            $dataMessage = KinChat::where('patient_id',  $patientid)->count();
            if($dataMessage > 0){
                return response()->json(['error'=> TRUE, 'error_message'=>"Message already sent"]);
            }else {

    
            $data = new \App\KinChat();
            $data->kin_chat_message = $message;
            $data->kin_chat_status_sent = 1;
            $data->kin_id = $kinid;
            $data->patient_id = $patientid;
            $data->save();
            return response()->json(['error'=> FALSE,'success'=>$data], $this-> successStatus); 
          }
        }
        

    }

   

    public function getChat() 
    { 
        $user = Auth::user();
        $userid = $user->id;
        $kin = Kin::where('user_id',  $userid)->first();
        $kinid = $kin->kin_id;
        $patient = Patient::where('kin_id',  $kinid)->first();
        $patientid = $patient->patient_id;
        $chat = PPUKMChat::where('patient_id',  $patientid)->first()->all();


        return response()->json(['error'=> FALSE, 'success'=>$chat], $this-> successStatus); 

        

    }

    public function updateStatusChat() 
    { 
        $user = Auth::user();
        $userid = $user->id;
        $kin = Kin::where('user_id',  $userid)->first();
        $kinid = $kin->kin_id;
        $patient = Patient::where('kin_id',  $kinid)->first();
        $patientid = $patient->patient_id;
        $chat = PPUKMChat::where('patient_id',  $patientid)->count();

        if($chat > 0)
          {
            PPUKMChat::where('patient_id', $chat)->update(array(
                    'ppukm_chat_status_read'=>1,

                ));
            return response()->json(['error'=> FALSE, 'success' => "Your message has been read"], $this-> successStatus);     
          }

        

    }
    
}
