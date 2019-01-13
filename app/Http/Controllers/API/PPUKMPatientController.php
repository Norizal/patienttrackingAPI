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
    
}
