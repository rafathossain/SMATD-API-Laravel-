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

class LoginController extends Controller
{

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function CreateUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $response = new ResponseJSON();
            $validator = Validator::make($request->all(), [
                'rfid' => 'required',
                'name' => 'required',
                'mobile' => 'required',
                'roll' => 'required',
                'class' => 'required',
                'address' => 'required',
                'father' => 'required',
                'mother' => 'required',
                'age' => 'required',
                'bGroup' => 'required',
                'admin' => 'required'
            ]);

            if ($validator->fails()) {
                $response->prepare('996');
            } else {
                $admin = $request->input('admin');

                if ($admin == "8695") {
                    $rfid = $request->input('rfid');
                    $registered_users = studentInfo::select('*')
                        ->where('rfid', '=', $rfid)
                        ->get();
                    if (count($registered_users) == 0) {
                        $name = $request->input('name');
                        $mobile = $request->input('mobile');
                        $roll = $request->input('roll');
                        $class = $request->input('class');
                        $address = $request->input('address');
                        $father = $request->input('father');
                        $mother = $request->input('mother');
                        $age = $request->input('age');
                        $bGroup = $request->input('bGroup');

                        $new_student = new studentInfo();
                        $new_student->rfid = $rfid;
                        $new_student->name = $name;
                        $new_student->mobile = $mobile;
                        $new_student->roll = $roll;
                        $new_student->class = $class;
                        $new_student->address = $address;
                        $new_student->father = $father;
                        $new_student->mother = $mother;
                        $new_student->age = $age;
                        $new_student->bGroup = $bGroup;

                        $new_student_login = new login();
                        $new_student_login->rfid = $rfid;
                        $new_student_login->mobile = $mobile;
                        $new_student_login->password = "NOT_SET";

                        if ($new_student->save() && $new_student_login->save()) {
                            $response->prepare('100');
                            $data = array();
                            $data['RFID'] = $rfid;
                            $data['NAME'] = $name;
                            $data['MOBILE'] = $mobile;
                            $response->make($data);
                        } else {
                            $response->prepare('998');
                        }
                    } else {
                        $response->prepare('997');
                    }
                } else {
                    $response->prepare('994');
                }
            }
            return $response->show();
        }
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $response = new ResponseJSON();
            $validator = Validator::make($request->all(), [
                'rfid' => 'required',
                'mobile' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                $response->prepare('996');
            } else {
                $rfid = $request->input('rfid');
                $password = $request->input('password');

                $update_password = login::where('rfid', $rfid)
                    ->update(['password' => $password]);
                if ($update_password) {
                    $response->prepare('103');
                } else {
                    $response->prepare('998');
                }
            }
            return $response->show();
        }
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $response = new ResponseJSON();
            $validator = Validator::make($request->all(), [
                'rfid' => 'required',
                'mobile' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                $response->prepare('996');
            } else {
                $rfid = $request->input('rfid');
                $mobile = $request->input('mobile');
                $password = $request->input('password');
                $password_db = "";

                $valid_user = login::select('*')
                    ->where([
                        ['rfid', '=', $rfid],
                        ['mobile', '=', $mobile]
                    ])
                    ->get();

                if (count($valid_user) == 1) {
                    foreach ($valid_user as $valid_user_info) {
                        $password_db = $valid_user_info->password;
                        break;
                    }
                } else {
                    $response->prepare('999');
                }

                if ($password == $password_db) {
                    $response->prepare('104');
                } else {
                    $response->prepare('995');
                }
            }
            return $response->show();
        }
    }

    /**
     * Cheks for the registered user and returns the user information.
     *
     * @param  string  $auth_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function isValidUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $response = new ResponseJSON();
            $validator = Validator::make($request->all(), [
                'rfid' => 'required',
                'mobile' => 'required'
            ]);
            if ($validator->fails()) {
                $response->prepare('996');
            } else {
                $rfid = $request->input('rfid');
                $mobile = $request->input('mobile');

                $valid_user = login::select('*')
                    ->where([
                        ['rfid', '=', $rfid],
                        ['mobile', '=', $mobile]
                    ])
                    ->get();

                if (count($valid_user) == 1) {
                    foreach ($valid_user as $valid_user_info) {
                        $password = $valid_user_info->password;
                        break;
                    }
                    if ($password == "NOT_SET") {
                        $response->prepare('101');
                    } else {
                        $response->prepare('102');
                    }
                } else {
                    $response->prepare('999');
                }
            }
            return $response->show();
        }
    }
}
