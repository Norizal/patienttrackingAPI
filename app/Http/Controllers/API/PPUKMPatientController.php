<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\Patient;
use App\PPUKM;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;

class PPUKMPatientController extends Controller
{

    public $successStatus = 200;

    public function createPatient(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            $patient_hukm_id = $request->input('hukm_id') ,
            $beacon_id = $request->input('beacon_id'),
            $medical_status_id = $request->input('medical_status_id'),
               
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=> FALSE,'error_message'=>$validator->errors()], 401);            
        }else {

            $user = Auth::user();
            $user_id = $user->id;
            $data = PPUKM::where('user_id',  $user_id)->first();
            // $ppukm_id =  DB::select('SELECT ppukm.ppukm_id FROM ppukm WHERE user_id = :user_id', ['user_id' => $user_id]);
             $rand = $this->generateRandomString(6);
    
            $dataUser = new \App\Patient();
            $dataUser->hukm_id= $patient_hukm_id;
            $dataUser->beacon_id = $beacon_id;
            $dataUser->medical_status_id = $medical_status_id;
            $dataUser->location_id = 1;
            $dataUser->barcode = $rand;
            $dataUser->ppukm_id = $data->ppukm_id;
            $dataUser->save();
            return response()->json(['success'=>$dataUser], $this-> successStatus); 
          }
        

    }

    public function generateRandomString($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
     }

     
 

    public function getPatient(){

        $user = Auth::user()->id;
        $data = PPUKM::where('user_id',  $user)->first();
        $userPPUKM = $data->ppukm_id;


      
        $data = DB::table('patient')
        ->select('patient_hukm.hukm_name','patient_hukm.hukm_icnumber','patient_hukm.hukm_mrn','patient_hukm.hukm_gender','patient_hukm.hukm_age', 'patient_hukm.hukm_race', 'patient_hukm.hukm_phonenumber', 'location.location_name')
        ->join('patient_hukm', 'patient_hukm.hukm_id', '=', 'patient.hukm_id')
        ->join('location', 'location.location_id', '=', 'patient.location_id')
        ->leftjoin('kin_chat', 'kin_chat.patient_id', '=', 'patient.patient_id')
        ->select(DB::raw("count(kin_chat.kin_chat_id) as kin_chat"))
        ->where('patient.ppukm_id', $userPPUKM)
        ->groupBy('patient.patient_id')
        ->get();
        
        
        
        // select('SELECT id, hukm_id, beacon_id, FROM kin WHERE user_id = :id', ['id' => $user]);
        return response()->json(['success' => $data], $this-> successStatus); 


    }
    
}
