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
            $name = $request->input('name'),
            $uuid = $request->input('uuid'),
            $major = $request->input('major'),
            $minor = $request->input('minor')

        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=> FALSE,'error'=>$validator->errors()], 401);            
        }else {

    
            $dataUser = new \App\Beacon();
            $dataUser->name = $name;
            $dataUser->uuid = $uuid;
            $dataUser->major = $major;
            $dataUser->minor = $minor;
            $dataUser->save();
            return response()->json(['success'=>$dataUser], $this-> successStatus); 
          }
        

    }

    public function getBeacon($id) 
    { 
        $dataUser = Beacon::find($id);

        return response()->json(['success'=>$dataUser], $this-> successStatus); 

        

    }

   
    
}
