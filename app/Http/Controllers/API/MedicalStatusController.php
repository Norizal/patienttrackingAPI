<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\MedicalStatus;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;

class MedicalStatusController extends Controller
{

    public $successStatus = 200;

    public function createMedicalStatus(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name'=>'required' ,
            

        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=> FALSE,'error_message'=>$validator->errors()], 401);            
        }else {

            $name = $request->input('name');
            $dataName = MedicalStatus::where('medical_status_name',  $name)->count();
            if($dataName > 0){
                return response()->json(['error'=> TRUE, 'error_message'=>"Name already exist"]);
            }else {

    
            $data = new \App\MedicalStatus();
            $data->medical_status_name = $name;
            $data->save();
            return response()->json(['error'=> FALSE,'success'=>$data], $this-> successStatus); 
          }
        }
        

    }

    public function getMedicalStatusByID($id) 
    { 
        $dataStatus = MedicalStatus::find($id);

        return response()->json(['success'=>$dataStatus], $this-> successStatus); 

        

    }

    public function getMedicalStatus() 
    { 
        $dataStatuss = MedicalStatus::all();

        return response()->json(['success'=>$dataStatuss], $this-> successStatus); 

        

    }

   
    
}
