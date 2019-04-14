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
        $data = Kin::where('user_id',  $user)->first();
        $userKin = $data->kin_id;

      
        $patientInfo = DB::select('SELECT patient.patient_id as id, patient_hukm.hukm_name as name,patient_hukm.hukm_icnumber as icnumber,patient_hukm.hukm_mrn as mrn,patient_hukm.hukm_gender as gender, patient_hukm.hukm_age as age, patient_hukm.hukm_race as race, patient_hukm.hukm_phonenumber as phonenumber,medical_status.medical_status_name as medical_status, device.name as beacon_name, device.mac as beacon_mac, gateway.mac as gateway_mac, gateway.name as gateway_name FROM patient INNER JOIN  patient_hukm ON patient.hukm_id = patient_hukm.hukm_id INNER JOIN medical_status ON patient.medical_status_id = medical_status.medical_status_id INNER JOIN device ON patient.device_mac = device.mac INNER JOIN gateway ON patient.gateway_mac = gateway.mac   WHERE patient.kin_id = :kin_id ORDER BY patient_hukm.hukm_mrn ASC', ['kin_id' => $userKin]);
        
        
        
        // select('SELECT id, hukm_id, beacon_id, FROM kin WHERE user_id = :id', ['id' => $user]);
        return response()->json(['success' => $patientInfo], $this-> successStatus); 


    }

    public function getLocationHistoryByID($patID) 
    { 
        $dataPatientLocation = Patient::where('patient_id', $patID)->first();
        $DMac= $dataPatientLocation->device_mac;

        $dataLocation = DB::select('SELECT patient.patient_id as id, gateway.name as location, DATE_FORMAT(status.updated_at,"%r %e/%c/%Y") as time FROM patient INNER JOIN device ON patient.device_mac = device.mac LEFT JOIN status ON device.mac = status.mac LEFT JOIN gateway ON gateway.mac = status.gateway_mac WHERE patient.device_mac = :device_mac ORDER BY status.updated_at DESC', ['device_mac' => $DMac]);
        
        return response()->json(['error'=> FALSE,'success' => $dataLocation], $this-> successStatus); 
        
        

    }
}
