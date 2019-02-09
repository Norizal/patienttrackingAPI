<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Beacon;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;

class BeaconController extends Controller
{

    public $successStatus = 200;

    public function createBeacon(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name'=>'required' ,
            'uuid'=>'required', 
            'major'=>'required',
            'minor'=>'required', 
            
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=> TRUE,'error_message'=>$validator->errors()], 401);            
        }else {

            $name = $request->input('name');
            $uuid = $request->input('uuid');
            $major = $request->input('major');
            $minor = $request->input('minor');

            $dataUUID = Beacon::where('beacon_uuid',  $uuid)->count();

            if($dataUUID >0){
                return response()->json(['error'=> TRUE, 'error_message'=>"Beacon UUID already exist"]); 
            }else{
                $dataName = Beacon::where('beacon_name',  $name)->count();
                if($dataName > 0){
                    return response()->json(['error'=> TRUE, 'error_message'=>"Beacon Name already exist"]);
                }else {
                        $data = new \App\Beacon();
                        $data->beacon_name = $name;
                        $data->beacon_uuid = $uuid;
                        $data->beacon_major = $major;
                        $data->beacon_minor = $minor;
                        $data->save();
                        return response()->json(['error'=>FALSE,'success'=>$data], $this-> successStatus); 
                   
                }
            }


            return response()->json(['error'=> TRUE, 'error_message' => 'Internal Server Error' ],500);

    
            
          }
        

    }

    public function getBeaconByID($beacon_name) 
    { 
        $dataBeacon = Beacon::where('beacon_name','like', $beacon_name.'%')->get();

       
        return response()->json(['error'=> FALSE,'success'=>$dataBeacon], $this-> successStatus); 
        
        

    }

    public function getBeacon(){

        $dataBeacons = Beacon::all();
       
        return response()->json(['error'=> FALSE,'success'=>$dataBeacons], $this-> successStatus); 

    }

   
    
}
