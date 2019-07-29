<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseJSON;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Models\login;
use App\Models\studentInfo;
use Illuminate\Support\Facades\Hash;
use App\Models\Attendance;

class StudentController extends Controller
{
    public function studentInfo(Request $request)
    {
        if ($request->isMethod('post')) {
            $response = new ResponseJSON();
            $validator = Validator::make($request->all(), [
                'rfid' => 'required'
            ]);

            if ($validator->fails()) {
                $response->prepare('996');
            } else {
                $rfid = $request->input('rfid');
                $registered_users = studentInfo::select('*')
                    ->where('rfid', '=', $rfid)
                    ->get();
                if (count($registered_users) == 1) {
                    foreach($registered_users as $student){
                        $name = $student->name;
                        $mobile = $student->mobile;
                        $roll = $student->roll;
                        $class = $student->class;
                        $address = $student->address;
                        $father = $student->father;
                        $mother = $student->mother;
                        $age = $student->age;
                        $bGroup = $student->bGroup;
                        $response->prepare('100');
                        $data = array();
                        $data['RFID'] = $rfid;
                        $data['NAME'] = $name;
                        $data['MOBILE'] = $mobile;
                        $data['ROLL'] = $roll;
                        $data['CLASS'] = $class;
                        $data['ADDRESS'] = $address;
                        $data['FATHER'] = $father;
                        $data['MOTHER'] = $mother;
                        $data['AGE'] = $age;
                        $data['BLOODGROUP'] = $bGroup;
                        $response->make($data);
                    }
                } else {
                    $response->prepare('999');
                }
            }
            return $response->show();
        }
    }

    public function attendanceReport(Request $request){
        if ($request->isMethod('post')) {
            $response = new ResponseJSON();
            $validator = Validator::make($request->all(), [
                'rfid' => 'required'
            ]);

            if ($validator->fails()) {
                $response->prepare('996');
            } else {
                $rfid = $request->input('rfid');
                $month = date('m');
                
                $current = Attendance::select('*')
                                        ->where([
                                            ['rfid', '=', $rfid],
                                            ['month', '=', $month]
                                        ])
                                        ->get();
                
                $previous = Attendance::select('*')
                                        ->where([
                                            ['rfid', '=', $rfid],
                                            ['month', '=', $month-1]
                                        ])
                                        ->get();

                $response->prepare('100');
                $data = array();
                $data['CURRENT'] = count($current);
                $data['PREVIOUS'] = count($previous);
                $response->make($data);
            }
            return $response->show();
        }
    }

    public function recordAttendance(Request $request){
        if ($request->isMethod('post')) {
            $response = new ResponseJSON();
            $validator = Validator::make($request->all(), [
                'rfid' => 'required',
                'date' => 'required',
                'month' => 'required'
            ]);

            if ($validator->fails()) {
                $response->prepare('996');
            } else {
                $rfid = $request->input('rfid');
                $month = $request->input('month');
                $date = $request->input('date');
                
                $check_attd = Attendance::select('*')
                                        ->where([
                                            ['rfid', '=', $rfid],
                                            ['date', '=', $date],
                                            ['month', '=', $month]
                                        ])
                                        ->get();
                
                if(count($check_attd) == 1){
                    $response->prepare('993');
                } else {
                    $new_record = new Attendance();
                    $new_record->rfid = $rfid;
                    $new_record->date = $date;
                    $new_record->month = $month;
                    if($new_record->save()){
                        $response->prepare('100');
                    } else {
                        $response->prepare('998');
                    }

                    $registered_users = studentInfo::select('*')
                                            ->where('rfid', '=', $rfid)
                                            ->get();

                    if (count($registered_users) == 1) {
                        foreach($registered_users as $student){
                            $name = $student->name;
                            
                            $curl = curl_init();

                            curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://tridivroy.xyz/smatd/push.php",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => false,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => array('name' => $name),
                            ));

                            $responsaae = curl_exec($curl);
                            // $err = curl_error($curl);

                            curl_close($curl);

                            // if ($err) {
                            // echo "cURL Error #:" . $err;
                            // } else {
                            // echo $response;
                            // }

                            break;
                        }
                    }
                }
            }
            return $response->show();
        }
    }
}
