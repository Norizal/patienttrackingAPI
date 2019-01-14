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
        $dataKin = Kin::where('user_id',  $user)->first();
        $userKin = $dataKin->kin_id;

        $dataPatient = Patient::where('kin_id',  $userKin)->first();

        $userPatient = $dataPatient->hukm_id;
        $userLocation = $dataPatient->location_id;
        $userBeacon = $dataPatient->beacon_id;
        $userMedicalStatus = $dataPatient->medical_status_id;

        $dataHUKM = PatientHUKM::where('hukm_id',  $userPatient)->first();

        $data = $dataHUKM->hukm_icnumber;
        $data = $dataHUKM->hukm_name;
        $data = $dataHUKM->hukm_mrn;
        $data = $dataHUKM->hukm_gender;
        $data = $dataHUKM->hukm_age;
        $data = $dataHUKM->hukm_race;
        $data = $dataHUKM->hukm_phonenumber;

        $dataLocation = Location::where('location_id',  $userLocation)->first();

        $data = $dataLocation->location_name;

        $dataBeacon = Beacon::where('beacon_id',  $userBeacon)->first();

        $data = $dataBeacon->beacon_name;

        $dataMedicalStatus = MedicalStatus::where('medical_status_id',  $userMedicalStatus)->first();

        $data = $dataMedicalStatus->medical_status_name;



      
        // $data = DB::table('patient')
        // ->select('patient_hukm.hukm_name','patient_hukm.hukm_icnumber','patient_hukm.hukm_mrn','patient_hukm.hukm_gender','patient_hukm.hukm_age', 'patient_hukm.hukm_race', 'patient_hukm.hukm_phonenumber', 'location.location_name')
        // ->join('patient_hukm', 'patient_hukm.hukm_id', '=', 'patient.hukm_id')
        // ->join('location', 'location.location_id', '=', 'patient.location_id')
        // ->where('patient.kin_id', $userKin)
        // ->get();
        
        
        
        // select('SELECT id, hukm_id, beacon_id, FROM kin WHERE user_id = :id', ['id' => $user]);
        return response()->json(['success' => $data], $this-> successStatus); 


    }
}
