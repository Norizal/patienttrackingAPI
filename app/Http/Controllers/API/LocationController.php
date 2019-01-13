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
            'name'=>'required' ,
            
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=> FALSE,'error_message'=>$validator->errors()], 401);            
        }else {

            $name = $request->input('name');
            $dataName = Location::where('location_name',  $name)->count();
            if($dataName > 0){
                return response()->json(['error'=> TRUE, 'error_message'=>"Location Name already exist"], 409);
            }else {

    
            $data= new \App\Location();
            $data->location_name = $name;
            $data->save();
            return response()->json(['error'=> FALSE,'success'=>$dataUser], $this-> successStatus); 
          }
        }
        

    }

    public function getLocationByID($id) 
    { 
        $dataLocation = Location::find($id);

        return response()->json(['error'=> FALSE,'success'=>$dataLocation], $this-> successStatus); 

        

    }

    public function getLocation() 
    { 
        $dataLocations = Location::all();

        return response()->json(['error'=> FALSE,'success'=>$dataLocations], $this-> successStatus); 

        

    }

   
    
}
