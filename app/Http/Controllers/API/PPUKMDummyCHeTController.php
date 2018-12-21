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
            $icnumber = $request->input('icnumber') ,
            $name = $request->input('name'),
            $mrn = $request->input('mrn'),
            $gender = $request->input('gender'),
            $age = $request->input('age'),
            $race = $request->input('race'),
            $phonenumber = $request->input('phonenumber')      
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=> FALSE,'error'=>$validator->errors()], 401);            
        }else {

            $dataUser = new \App\PatientHUKM();
            $dataUser->icnumber = $icnumber;
            $dataUser->name = $name;
            $dataUser->mrn = $mrn;
            $dataUser->gender = $gender;
            $dataUser->age = $age;
            $dataUser->race = $race;
            $dataUser->phonenumber = $phonenumber;
            $dataUser->save();
            return response()->json(['success'=>$dataUser], $this-> successStatus); 
          }
        

    }

    public function getPatientHUKM($id) 
    { 
        $dataUser = PatientHUKM::find($id);

        return response()->json(['success'=>$dataUser], $this-> successStatus); 

        

    }

   
    
}
