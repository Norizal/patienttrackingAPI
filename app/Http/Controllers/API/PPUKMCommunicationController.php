<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\PPUKM;
use App\Patient;
use App\PPUKMChat;
use App\KinChat;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;

class PPUKMCommunicationController extends Controller
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
            $ppukm = PPUKM::where('user_id',  $userid)->first();
            $ppukmid = $ppukm->ppukm_id;
            $patient = Patient::where('ppukm_id',  $ppukmid)->first();
            $patientid = $patient->patient_id;
            
            $message= $request->input('message');


            $dataMessage = PPUKMChat::where('patient_id',  $patientid)->count();
            if($dataMessage > 0){
                return response()->json(['error'=> TRUE, 'error_message'=>"Message already sent"]);
            }else {

    
            $data = new \App\PPUKMChat();
            $data->ppukm_chat_message = $message;
            $data->ppukm_chat_status_sent = 1;
            $data->ppukm_id = $ppukmid;
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
        $ppukm = PPUKM::where('user_id',  $userid)->first();
        $ppukmid = $ppukm->ppukm_id;
        $patient = Patient::where('ppukm_id',  $ppukmid)->first();
        $patientid = $patient->patient_id;
        $chat = PPUKMChat::where('patient_id',  $patientid)->first()->all();


        return response()->json(['success'=>$chat], $this-> successStatus); 

        

    }

    public function updateStatusChat() 
    { 
        $user = Auth::user();
        $userid = $user->id;
        $ppukm = PPUKM::where('user_id',  $userid)->first();
        $ppukmid = $ppukm->ppukm_id;
        $patient = Patient::where('ppukm_id',  $ppukmid)->first();
        $patientid = $patient->patient_id;
        $chat = KinChat::where('patient_id',  $patientid)->count();

        if($chat > 0)
          {
            KinChat::where('patient_id', $chat)->update(array(
                    'kin_chat_status_read'=>1,

                ));
            return response()->json(['error'=> FALSE, 'success' => "Your message has been read"], $this-> successStatus);     
          }

        

    }
    
}
