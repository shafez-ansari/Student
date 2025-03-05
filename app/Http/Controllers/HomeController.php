<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Mail\OtpMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\CROController;

class HomeController extends Controller
{
    public function Login(Request $req)
    {
        
            $email = $req->email;
            $userList = DB::select("SELECT u.user_id FROM users u
                                    LEFT JOIN students s ON u.user_id = s.student_id WHERE u.email = ? OR s.unique_id = ?", [$email, $email]);
            
            if(count($userList) == 0)
            {
                return response()->json(['loginMsg' => "Invalid email or unique Id"]);
            }
            else 
            {
                $userId = 0;
                foreach($userList as $user)
                {
                    $userId = $user->user_id;
                }

                $randomNumber = random_int(1000, 9999);
                DB::table('email_otp')->insert([
                    'otp' => $randomNumber,
                    'fk_user_id' => $userId,
                    'created_by' => $email,
                    'updated_by' => $email,
                    'created_date' => now(),
                    'updated_date' => now(),
                    'active' => 1
                ]);

                Mail::send('otp-mail', ['otp' => $randomNumber], function($message) use ($req) {
                    $message->to($req->email);
                    $message->subject('Your OTP Code');
                });

                return response()->json(['loginMsg' => 'OTP generated']);
            }
        
        
    }

    public function SubmitOtp(Request $req)
    {
        
            $email = $req->email;
            $otp = $req->otp;
            $userList = DB::select("SELECT * FROM users u
                                    LEFT JOIN students s ON u.user_id = s.fk_user_id
                                    LEFT JOIN role r on r.role_id = u.fk_role_id
                                    WHERE LOWER(u.email) = ? OR LOWER(s.unique_id) = ? AND u.active = 1", [strtolower($email), strtolower($email)]);

            $userId = 0;
            if(count($userList) == 0)
            {
                return response()->json(['loginMsg' => 'Invalid email or unique Id']);
            }
            else
            {
                foreach($userList as $user)
                {
                    $userId = $user->user_id;
                }
            }

            $otpList = DB::select("SELECT * FROM email_otp WHERE active = 1 AND fk_user_id = ? AND otp = ? AND created_date >= NOW() - INTERVAL 10 MINUTE", [$userId, $otp]);

            if(count($otpList) == 0)
            {            
                return response()->json(['loginMsg' => 'Invalid OTP']);
            }
            else 
            {
                $roleId = 0;
                foreach($userList as $user)
                {
                    $roleId = $user->fk_role_id;
                    session()->put('username', $user->email); 
                    session()->put('role', $user->role_name);
                }

                $roleName = DB::table('role')->where('role_id', '=', $roleId)->value('role_name');
                
                if($roleName == 'Student')
                {
                    return response()->json(["loginMsg" => url("student-details")]);
                    //return redirect()->action([HomeController::class, 'StudentDetails']);
                    // $userList = DB::select("SELECT u.user_id, u.fk_role_id, u.full_name, u.email, u.phone, u.unique_id, e.entity_name, e.entity_code, s.school_name, s.school_code, c.course_name, c.course_code, u.batch_code, u.semester, u.enrollment_datr 
                    //                 FROM users u
                    //                 LEFT JOIN entity e ON e.entity_id = u.fk_entity_id
                    //                 LEFT JOIN school s ON s.school_id = u.fk_school_id
                    //                 LEFT JOIN courses c ON c.course_id = u.fk_course_id
                    //                 LEFT JOIN program p ON p.program_id = u.fk_program_id
                    //                 WHERE u.email = ?", [session('email')]);

                    // return view('home.student-details', compact(['userList']));
                }
                else if($roleName == "CRO")
                {
                    return response()->json(["loginMsg" => url("cro-details")]);
                    //return redirect()->action([HomeController::class, 'CRODetails']);
                }
                else if($roleName == "Admin" )
                {
                    return redirect()->action([HomeController::class, 'AdminDetails']);
                }
                else
                {
                    return redirect()->action([HomeController::class, 'ITDetails']);
                }
            }
        
    }

    public function ResendOtp(Request $req)
    {
        
            $email = $req->email;
            $userList = DB::select("SELECT * FROM users u
                                    LEFT JOIN students s ON u.user_id = s.fk_user_id
                                    WHERE u.email = ? OR s.unique_id = ?", [$email, $email]);
                                    
            $userId = 0;
            foreach($userList as $user)
            {
                $userId = $user->user_id;
            }

            $randomNumber = random_int(1000, 9999);

            DB::table('email_otp')->where('fk_user_id', $userId)->update([
                'active' => 0
            ]);
            
            DB::table('email_otp')->insert([
                'otp' => $randomNumber,
                'fk_user_id' => $userId,
                'created_by' => $email,
                'updated_by' => $email,
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);

            Mail::send('otp-mail', ['otp' => $randomNumber], function($message) use ($req) {
                $message->to($req->email);
                $message->subject('Your OTP Code');
            });

            return response()->json(['loginMsg' => 'OTP resend generated']);
        
    }

    public function StudentDetails()
    {
        if(session('username') != "" && session('role') == "Student")
        {
            $userList = DB::select("SELECT u.user_id, u.fk_role_id, u.full_name, u.email, u.phone, stu.unique_id, e.entity_name, e.entity_code, s.school_name, s.school_code, c.course_name, c.course_code, stu.batch_code, stu.semester_code, stu.enrollment_date, ui.img_name, ui.img_path 
                                    FROM users u
                                    LEFT JOIN students stu ON u.user_id = stu.fk_user_id
                                    LEFT JOIN entity e ON e.entity_id = stu.fk_entity_id
                                    LEFT JOIN school s ON s.school_id = stu.fk_school_id
                                    LEFT JOIN courses c ON c.course_id = stu.fk_course_id
                                    LEFT JOIN program p ON p.program_id = stu.fk_program_id
                                    LEFT JOIN user_image ui on ui.fk_user_id = u.user_id
                                    WHERE u.email = ?", [session('username')]);

            return view('home.student-details', compact(['userList']));
        }
        else 
        {
            return view('home.home-view');
        }
    }

    public function SubmitPlacement($entityName, $school, $yes, $no, $course)
    {
        if(session('username') != "" && session('role') == "Student")
        {
            // $entityName = $req->entity;
            // $school = $req->school;
            // $course = $req->course;
            // $yes = $req->yes;
            // $no = $req->no;
            $jobProfile = "";
            $academicQual = "";
            $jobType = "";
            $empLocation = "";
            $state = "";
            $city = "";
            $empStatus = "";
            $careerSupport = "";
            $reasonNotPlacing = "";
            $jobRoles = "";
            $jobRelocate = "";
            $workType = "";
            $reasonNotPlacing = "";

            $userId = DB::table('users')->where('email', '=', session('username'))->value('user_id');
            $offline_questionarie = DB::table('offline_questionarie')->where('fk_user_id', '=', $userId)->value('noida_question_id');
            $online_questionarie = DB::table('online_questionarie_yes')->where('fk_user_id', '=', $userId)->value('online_questionarie_id');
            $notPlacement = DB::table('questionarie_no')->where('fk_user_id', '=', $userId)->value('questionarie_no_id');
            if($offline_questionarie == 0 && $online_questionarie == 0 && $notPlacement == 0)
            {
                if($yes == 1 )
                {
                    $academicQual = DB::select("SELECT qualification_id, qualification_name FROM academic_qualification WHERE active = 1");
                    $questionarieId = DB::table('offline_questionarie')->where('fk_user_id', '=', $userId)->value('noida_question_id');
                    $jobProfile = DB::select("SELECT jp.job_profile_id, jp.profile_name 
                                                FROM job_profile jp
                                                WHERE (? IS NULL OR jp.school_name = ?) 
                                                OR (? IS NULL OR jp.school_name = ?)", [$school, $school, $course, $course]);

                    if($entityName == "AAFT Noida" || $entityName == "AAFT University" )
                    { 
                        $jobType = DB::select("SELECT job_type_id, job_type_name FROM job_type WHERE active = 1");
                        $empLocation = DB::select("SELECT emp_loc_id, emp_loc_name FROM employment_location WHERE active = 1");
                    }
                    else if($entityName == "AAFT Online")
                    {
                        $state = DB::select("SELECT state_id, state_name FROM state");
                        $city = DB::select("SELECT city_id, city_name FROM city");
                        $empStatus = DB::select("SELECT emp_status_id, emp_status_name FROM employment_status");
                        $careerSupport = DB::select("SELECT career_id, career_name FROM career_support");
                        
                        $jobRoles = DB::select("SELECT job_role_id, job_role_name FROM job_role");
                        $jobRelocate = DB::select("SELECT job_relocate_id, job_relocate_name FROM job_relocate");
                        $workType = DB::select("SELECT work_type_id, work_type_name FROM work_type");
                    }   
                }
                else if($no == 1)
                {
                    $reasonNotPlacing = DB::select("SELECT not_placement_id, not_placement_name FROM not_placement WHERE active = 1");
                }
            }
            else 
            {
                return redirect()->action([HomeController::class, 'ThankYou']);
            }
            
            return view('home.submit-placement', compact(['entityName', 'school', 'course', 'jobProfile', 'academicQual', 'jobType', 'empLocation', 'state', 'city',
            'empStatus', 'careerSupport', 'jobRoles', 'jobRelocate', 'workType', 'reasonNotPlacing', 'yes', 'no']));
        }
        else 
        {
            return view('home.home-view');
        }
    }

    public function SubmitQuestionarie(Request $req)
    {
        if(session('username') != "" && session('role') == "Student")
        {
            $userId = DB::table('users')->where('email', '=', session('username'))->value('user_id');
            
            if($req->yes == 1)
            {
                $academicQual = $req->qualId;
                $entity = $req->entityName;
                $course = $req->course;
                $school = $req->school;                
                $entityId = DB::table('entity')->where('entity_name', '=', $entity)->value('entity_id');
                $schoolId = DB::table('school')->where('school_name', '=', $school)->value('school_id');
        
                if($entity == "AAFT Noida" || $entity == "AAFT University")
                {
                    $jobProfile = is_array($req->jobProfileId) ? implode(', ', $req->jobProfileId) : '';
                    $jobType = is_array($req->jobTypeId) ? implode(', ', $req->jobTypeId) : '';
                    $jobLocation = is_array($req->jobLocationId) ? implode(', ', $req->jobLocationId) : '';
                    $workExp = $req->workExp ?? ''; // Ensure work experience is not null
                    $questionarieId = DB::table('offline_questionarie')->where('fk_user_id', '=', $userId)->value('noida_question_id');
                    if($questionarieId == 0)
                    {
                        DB::table('offline_questionarie')->insert([
                            'fk_qual_id' => $academicQual, // Assuming this is a sanitized input
                            'fk_user_id' => $userId, // Assuming this is a sanitized input
                            'job_type' => $jobType, // Stored as a string
                            'job_profile' => $jobProfile, // Stored as a string
                            'fk_entity_id' => $entityId, // Assuming this is a sanitized input
                            'fk_school_id' => $schoolId, // Assuming this is a sanitized input
                            'emp_location' => $jobLocation, // Stored as a string
                            'work_exp' => $workExp, // Assuming this is a sanitized input
                            'created_by' => session('username') ?? 'system',
                            'updated_by' => session('username') ?? 'system',
                            'created_date' => now(),
                            'updated_date' => now(),
                            'active' => 1
                        ]);
                    }
                }
                else if($entity == "AAFT Online")
                {
                    $state = $req->stateId;
                    $city = $req->cityId;
                    $empStatus = $req->empStatusId;
                    $careerSupport = $req->careerSupportId;
                    $technicalSkill = $req->techSkillId;
                    $jobRole = implode(', ',$req->jobProfileId);
                    $preferedJob = $req->preferedJob;
                    $relocate = $req->relocate;
                    $jobPlace = $req->jobPlace;
                    $workType = $req->workType;
                    $questionarieId = DB::table('online_questionarie_yes')->where('fk_user_id', '=', $userId)->value('online_questionarie_id');
                    if($questionarieId == 0)
                    {
                        DB::table('online_questionarie_yes')->insert([
                            'fk_state_id' => $state,
                            'fk_city_id' => $city,
                            'fk_qualification_id' => $academicQual,
                            'fk_employment_status_id' => $empStatus,
                            'fk_career_id' => $careerSupport,
                            'technical_skill' => $technicalSkill,
                            'job_role' => $jobProfileId,
                            'fk_job_role_id' => $jobRolesId,
                            'fk_relocate_id' => $jobRelocateId,
                            'fk_emp_loc_id' => $jobLocationId,
                            'fk_work_type_id' => $workTypeId,
                            'fk_user_id' => $userId,
                            'created_by' => session('username'),
                            'updated_by' => session('username'),
                            'created_date' => now(),
                            'updated_date' => now(),
                            'active' => 1   
                        ]);
                    }
                }
            }
            else 
            {
                $intervalId = $req->intervalId;
                $notPlacementId = $req->notPlacementId;
                $questionarieId = DB::table('questionarie_no')->where('fk_user_id', '=', $userId)->value('questionarie_no_id');
                if($questionarieId == 0)
                {
                    DB::table('questionarie_no')->insert([
                        'fk_placement_no_id' => $notPlacementId,
                        'fk_user_id' => $userId,
                        'interval' => $intervalId,
                        'created_by' => session('username'),
                        'updated_by' => session('username'),
                        'created_date' => now(),
                        'updated_date' => now(),
                        'active' => 1   
                    ]);
                }
            }

            return response()->json(["thankyou" => "thankYou"]);
        }
        else 
        {
            return view('home.home-view');
        }
    }

    public function GetCity(Request $req)
    {
        if(session('username') != "" && session('role') == "Student")
        {
            $stateId = $req->stateId;
            $cityList = DB::select("SELECT city_id, city_name FROM city WHERE fk_state_id = ?", [$stateId]);
            return response()->json(['cityList' => cityList]);
        }
        else 
        {
            return view('home.home-view');
        }
    }

    public function ThankYou()
    {
        if(session('username') != "" && session('role') == "Student")
        {
            $fullName = DB::table('users')->where('email', '=', session('username'))->value('full_name');
            $uniqueId = DB::table('users')->join('students', 'users.user_id', '=', 'students.fk_user_id')->where('email', '=', session('username'))->value('unique_id');
            return view('home.thank-you', compact(['fullName', 'uniqueId']));
        }
        else 
        {
            return view('home.home-view');
        }
    }

    public function Logout()
    {        
        session()->flush();
        return view('home.home-view');
    }
    
}
