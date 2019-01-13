<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\Kin;
use App\Patient;
use App\Location;
use App\MedicalStatus;
use App\PatientHUKM;
use App\Beacon;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Parser;

class KinPatientController extends Controller
{

    public $successStatus = 200;
 

    public function getPatient(){

        $user = Auth::user()->id;

      
        $data = DB::table('patient')
        ->select('patient_hukm.name','patient_hukm.icnumber','patient_hukm.mrn','patient_hukm.gender','patient_hukm.age', 'patient_hukm.race', 'patient_hukm.phonenumber', 'location.name')
        ->join('patient_hukm', 'patient_hukm.id', '=', 'patient.hukm_id')
        ->join('location', 'location.id', '=', 'patient.location_id')
        ->where('patient.kin_id', $user)
        ->get();
        
        
        
        // select('SELECT id, hukm_id, beacon_id, FROM kin WHERE user_id = :id', ['id' => $user]);
        return response()->json(['success' => $data], $this-> successStatus); 


    }
}
