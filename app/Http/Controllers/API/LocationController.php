<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Location;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;

class LocationController extends Controller
{

    public $successStatus = 200;

    public function createLocation(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            $name = $request->input('name')     
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=> FALSE,'error'=>$validator->errors()], 401);            
        }else {

    
            $dataUser = new \App\Location();
            $dataUser->name = $name;
            $dataUser->save();
            return response()->json(['success'=>$dataUser], $this-> successStatus); 
          }
        

    }

    public function getLocation($id) 
    { 
        $dataUser = Location::find($id);

        return response()->json(['success'=>$dataUser], $this-> successStatus); 

        

    }

   
    
}
