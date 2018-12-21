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
            $name = $request->input('name')

        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=> FALSE,'error'=>$validator->errors()], 401);            
        }else {

    
            $dataUser = new \App\MedicalStatus();
            $dataUser->name = $name;
            $dataUser->save();
            return response()->json(['success'=>$dataUser], $this-> successStatus); 
          }
        

    }

    public function getMedicalStatus($id) 
    { 
        $dataUser = MedicalStatus::find($id);

        return response()->json(['success'=>$dataUser], $this-> successStatus); 

        

    }

   
    
}
