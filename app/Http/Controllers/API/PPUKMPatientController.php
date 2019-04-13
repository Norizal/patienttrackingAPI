<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\Patient;
use App\Location;
use App\MedicalStatus;
use App\PatientHUKM;
use App\Beacon;
use App\PPUKM;
use App\Device;
use App\PatientRelationship;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;

class PPUKMPatientController extends Controller
{

    public $successStatus = 200;

    public function getLocationHistoryByID($patID) 
    { 
        $dataPatientLocation = Patient::where('patient_id', $patID)->first();
        $DMac= $dataPatientLocation->device_mac;

        $dataLocation = DB::select('SELECT patient.patient_id as id, gateway.name as location, status.updated_at as time FROM patient INNER JOIN device ON patient.device_mac = device.mac LEFT JOIN status ON device.mac = status.mac LEFT JOIN gateway ON gateway.mac = status.gateway_mac WHERE patient.device_mac = :device_mac ORDER BY status.updated_at DESC', ['device_mac' => $DMac]);
        
        return response()->json(['error'=> FALSE,'success' => $dataLocation], $this-> successStatus); 
        
        

    }

    public function createPatient(Request $request) 
    { 
        
        $validator = Validator::make($request->all(), [ 
            'hukm_id'=>'required' , 
            'device_mac'=>'required' , 
            'medical_status_id'=>'required' , 
            
         ]);

         if ($validator->fails()) {
             return response()->json(['error'=> TRUE, 'error_message'=>$validator->errors()], 401);            
         }else {
                     $hukmID = $request->input('hukm_id');
                     $deviceID= $request->input('device_mac');
                     $medicalID = $request->input('medical_status_id');

                     $dataPatient = Patient::where('hukm_id',  $hukmID)->count();
 
                     if($dataPatient > 0){
                         return response()->json(['error'=> TRUE, 'error_message'=>"Patient already exist"]);
                     }else{

                     $rand = $this->generateRandomString(6);

                     $user = Auth::user()->id;
                     $dataU = PPUKM::where('user_id',  $user)->first();
                     $userPPUKMID = $dataU->ppukm_id;

                     $dataPatientUser = new \App\Patient();
                     $dataPatientUser->hukm_id = $hukmID;
                     $dataPatientUser->device_mac = $deviceID;
                     $dataPatientUser->medical_status_id = $medicalID;
                     $dataPatientUser->barcode = $rand;
                     $dataPatientUser->ppukm_id = $userPPUKMID;

                          if($dataPatientUser->save()){

                               
                              
                                 $data = new \App\PatientRelationship;
     
                                 $data->ppukm_id = $userPPUKMID;
                                
 
                                     if($data->save()){ 
                                         $success['hukm_id'] =  $dataPatientUser->hukm_id;
                                         $success['device_id'] =  $dataPatientUser->device_mac;
                                         $success['medical_status_id'] =  $dataPatientUser->medical_status_id;
 
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

        $dataPatient = Patient::where('ppukm_id', $userPPUKM)->first();
        
    
        $ppukmID = $dataPatient->ppukm_id;

        $patientInfo = DB::select('SELECT patient.patient_id as id, patient_hukm.hukm_name as name,patient_hukm.hukm_icnumber as icnumber,patient_hukm.hukm_mrn as mrn,patient_hukm.hukm_gender as gender, patient_hukm.hukm_age as age, patient_hukm.hukm_race as race, patient_hukm.hukm_phonenumber as phonenumber,medical_status.medical_status_name as medical_status, device.name as beacon_name, device.mac as beacon_mac, gateway.mac as gateway_mac, gateway.name as gateway_name FROM patient INNER JOIN  patient_hukm ON patient.hukm_id = patient_hukm.hukm_id INNER JOIN medical_status ON patient.medical_status_id = medical_status.medical_status_id INNER JOIN device ON patient.device_mac = device.mac INNER JOIN gateway ON patient.gateway_mac = gateway.mac   WHERE patient.ppukm_id = :ppukm_id ORDER BY patient_hukm.hukm_mrn ASC', ['ppukm_id' => $ppukmID]);


    // $data = DB::table('patient')
    //     ->join('patient_hukm', 'patient_hukm.hukm_id', '=', 'patient.hukm_id')
    //     ->join('location', 'location.location_id', '=', 'patient.location_id')
    //     ->join('medical_status', 'medical_status.medical_status_id', '=', 'patient.medical_status_id')
    //     ->join('beacon', 'beacon.beacon_id', '=', 'patient.beacon_id')
    //     ->leftJoin('kin_chat', 'kin_chat.patient_id', '=', 'patient.patient_id')
    //     ->leftJoin('ppukm_chat', 'ppukm_chat.patient_id', '=', 'patient.patient_id')
    //     ->select('patient_hukm.hukm_name as name','patient_hukm.hukm_icnumber as icnumber','patient_hukm.hukm_mrn as mrn','patient_hukm.hukm_gender as gender',
    //     'patient_hukm.hukm_age as age', 'patient_hukm.hukm_race as race', 'patient_hukm.hukm_phonenumber as phonenumber', 
    //     'location.location_name as location', 'medical_status.medical_status_name as medical_status', 'beacon.beacon_name as beacon_name',
    //     DB::raw('IFNULL( kin_chat.kin_chat_status_sent, 0) as kin_chat_status_sent'),
    //     DB::raw('IFNULL( kin_chat.kin_chat_status_read, 0) as kin_chat_status_read'),
    //     DB::raw('IFNULL( ppukm_chat.ppukm_chat_status_sent, 0) as ppukm_chat_status_sent'),
    //     DB::raw('IFNULL( ppukm_chat.ppukm_chat_status_read, 0) as ppukm_chat_status_read'),    
    //     DB::raw("count(kin_chat.kin_chat_id) as kin_chat"), 
    //     DB::raw("count(ppukm_chat.ppukm_chat_id) as ppukm_chat"))
    //     ->where('patient.ppukm_id', $userPPUKM)
    //     ->groupBy('patient.ppukm_id')
    //     ->get();
        
        
        
        return response()->json(['error'=> FALSE,'success' => $patientInfo], $this-> successStatus); 


    }

    
    
}
