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
 

    public function getPatientKin(){

        $user = Auth::user()->id;
        $data = Kin::where('user_id',  $user)->first();
        $userKin = $data->kin_id;

      
        $data = DB::table('patient')
        ->select('patient_hukm.hukm_name','patient_hukm.hukm_icnumber','patient_hukm.hukm_mrn','patient_hukm.hukm_gender','patient_hukm.hukm_age', 'patient_hukm.hukm_race', 'patient_hukm.hukm_phonenumber', 'location.location_name')
        ->join('patient_hukm', 'patient_hukm.hukm_id', '=', 'patient.hukm_id')
        ->join('location', 'location.location_id', '=', 'patient.location_id')
        ->where('patient.kin_id', $user)
        ->get();
        
        
        
        // select('SELECT id, hukm_id, beacon_id, FROM kin WHERE user_id = :id', ['id' => $user]);
        return response()->json(['success' => $data], $this-> successStatus); 


    }
}
