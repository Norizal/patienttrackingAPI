<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\PatientHUKM;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;

class PPUKMDummyCHeTController extends Controller
{

    public $successStatus = 200;

    public function createPatientHUKM(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 

            'icnumber'=>'required' ,
            'name'=>'required', 
            'mrn'=>'required',
            'gender'=>'required', 
            'age'=>'required', 
            'race' => 'required',
            'phonenumber' => 'required',
              
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=> TRUE,'error_message'=>$validator->errors()], 401);            
        }else {
               
            $icnumber = $request->input('icnumber');
            $name = $request->input('name');
            $mrn = $request->input('mrn');
            $gender = $request->input('gender');
            $age = $request->input('age');
            $race = $request->input('race');
            $phonenumber = $request->input('phonenumber'); 

            $dataIC = PatientHUKM::where('hukm_icnumber',  $icnumber)->count();

            if($dataIC >0){
                return response()->json(['error'=> TRUE, 'error_message'=>"IC Number already exist"]); 
            }else{
                $dataMRN = PatientHUKM::where('hukm_mrn',  $mrn)->count();
                if($dataMRN > 0){
                    return response()->json(['error'=> TRUE, 'error_message'=>"MRN already exist"]);
                }else{
                    $dataUser = new \App\PatientHUKM();
                    $dataUser->hukm_icnumber = $icnumber;
                    $dataUser->hukm_name = $name;
                    $dataUser->hukm_mrn = $mrn;
                    $dataUser->hukm_gender = $gender;
                    $dataUser->hukm_age = $age;
                    $dataUser->hukm_race = $race;
                    $dataUser->hukm_phonenumber = $phonenumber;
                    $dataUser->save();
                    return response()->json(['error'=> FALSE, 'success'=>$dataUser], $this-> successStatus); 

                }

              
              }


              return response()->json(['error'=> TRUE, 'error_message' => 'Internal Server Error' ],500);
            }

            

           
        

    }

    public function getPatientHUKMByID($id) 
    {
        $patientID = PatientHUKM::where('hukm_icnumber',  $id)->first();
        $patientHUKM = $patientID->hukm_id;

        $data = DB::table('patient_hukm')
        ->select('patient_hukm.hukm_name as name','patient_hukm.hukm_icnumber as icnumber','patient_hukm.hukm_mrn as mrn','patient_hukm.hukm_gender as gender',
        'patient_hukm.hukm_age as age', 'patient_hukm.hukm_race as race', 'patient_hukm.hukm_phonenumber as phonenumber')
        ->where('patient_hukm.hukm_id', $patientHUKM)
        ->get();
    
        
        return response()->json(['error'=> FALSE,'success'=>$data], $this-> successStatus); 
        
    }

    public function getPatientHUKM() 
    { 
        $dataPatients = PatientHUKM::all();

        
        return response()->json(['error'=> FALSE,'success'=>$dataPatients], $this-> successStatus); 
        
    }

   
    
}
