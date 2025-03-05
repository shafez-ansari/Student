<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Mail\OtpMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;

class CROController extends Controller
{
    protected $client;
    protected $baseUrl;
    protected $accessKey;
    protected $secretKey;

    public function __construct()
    {
        $this->baseUrl = env('CRM_API_BASE_URL');
        $this->accessKey = env('CRM_API_ACCESS_KEY');
        $this->secretKey = env('CRM_API_SECRET_KEY');
        $this->client = new Client(['base_uri' => $this->baseUrl]);
    }

    public function CRODetails()
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            $entityList = DB::select("SELECT * FROM entity");
            // $school = DB::select("SELECT * FROM school");
            // $course = DB::select("SELECT * FROM courses");
            $optin = ['Yes', 'No', 'Did Not Fill Form'];
            $userList = DB::select("SELECT *
                FROM (
                    SELECT 
                        u.user_id, 
                        e.entity_name, 
                        s.school_name, 
                        c.course_code, 
                        u.full_name, 
                        u.email, 
                        u.phone, 
                        sch.unique_id, 
                        sch.batch_code, 
                        sch.semester_code, 
                        sch.enrollment_date, 
                        u.`active`,
                        sch.fk_entity_id,
                        sch.fk_school_id,
                        sch.fk_course_id,
                        r.role_name, 
                        CASE 
                            WHEN EXISTS (
                                SELECT 1 FROM offline_questionarie oq WHERE oq.fk_user_id = u.user_id
                            ) OR EXISTS (
                                SELECT 1 FROM online_questionarie_yes oqy WHERE oqy.fk_user_id = u.user_id
                            ) THEN 'YES'
                            WHEN EXISTS (
                                SELECT 1 FROM questionarie_no qn WHERE qn.fk_user_id = u.user_id
                            ) THEN 'NO'
                            ELSE 'DID NOT FILL FORM'
                        END AS OPTIN
                    FROM users u
                    LEFT JOIN students sch ON u.user_id = sch.fk_user_id
                    LEFT JOIN entity e ON e.entity_id = sch.fk_entity_id
                    LEFT JOIN school s ON s.school_id = sch.fk_school_id
                    LEFT JOIN courses c ON c.course_id = sch.fk_course_id
                    LEFT JOIN program p ON p.program_id = sch.fk_program_id
                    LEFT JOIN role r ON r.role_id = u.fk_role_id
                    WHERE r.role_name = 'Student' AND u.`active` = 1
                ) subquery
            ");

            return view('cro.dashboard', compact(['userList', 'entityList', 'optin']));
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function BulkUpload()
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            return view('cro.bulk');
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function BulkImageUpload()
    {
        // if(session('username') != "" && session('role') == "CRO")
        // {
            $entityList = DB::select("SELECT * FROM entity where active = 1");
            return view('cro.bulk-image', compact(['entityList']));
        // }
        // else
        // {
        //     return view('home.home-view');
        // }
    }

    public function ViewCompany()
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            $entityList = DB::select("SELECT * FROM entity");
            $programList = DB::select("SELECT * FROM program");
            $industrySectorList = DB::select("SELECT * FROM industry_sector");
            $industryLocationList = DB::select("SELECT * FROM industry_location");
            //return response()->json(['entityList' => $entityList, 'programList' => $programList, 'industrySectorList' => $industrySectorList, 'industryLocationList' => $industryLocationList]);
            return view('cro.add-company', compact(['entityList', 'programList', 'industrySectorList', 'industryLocationList']));
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function GetSchool(Request $request)
    {
        $entityId = $request->entity_id;
        $schoolList = DB::select("SELECT * FROM school WHERE fk_entity_id = ?", [$entityId]);
        return response()->json(['schoolList'=>$schoolList]);
    }

    public function GetCourse(Request $request)
    {
        $schoolId = $request->school_id;
        $courseList = DB::select("SELECT * FROM courses WHERE fk_school_id = ?", [$schoolId]);
        return response()->json(['courseList'=>$courseList]);
    }

    public function UploadStudent(Request $req)
    {
        if(session('username') != "" && session('role') == "CRO")
        {        
            $email = session('username');
            $errors = [];
            
            if ($req->hasFile('studentFile')) {
                $file = $req->file('studentFile');
                $path = $file->getRealPath();
                $mesg = '';
                $roleId = DB::table('role')->where('role_name', 'Student')->value('role_id');

                if (($handle = fopen($path, 'r')) !== false) {
                    // Skip the header row
                    $header = fgetcsv($handle, 1000, ',');
                    
                    // Prepare a batch insert array
                    $usersData = [];
                    $studentsData = [];

                    while (($row = fgetcsv($handle, 1000, ',')) !== false) 
                    {
                        // Fetch related IDs
                        $entityID = DB::table('entity')->whereRaw('LOWER(entity_name) = ?', [strtolower($row[3])])->value('entity_id');
                        $schoolId = DB::table('school')->whereRaw('LOWER(school_name) = ?', [strtolower($row[4])])->value('school_id');
                        $courseId = DB::table('courses')->whereRaw('LOWER(course_name) = ?', [strtolower($row[5])])->value('course_id');
                        $programId = DB::table('program')->whereRaw('LOWER(program_name) = ?', [strtolower($row[6])])->value('program_id');
                        //$convertedDate = Carbon::createFromFormat('d-m-Y', $row[10])->format('Y-m-d');
                        
                        if($entityID == null || $schoolId == null || $courseId == null || $programId == null)
                        {
                            return response()->json(['message' => 'Error reading the file.']);
                        }

                    }

                    while (($row = fgetcsv($handle, 1000, ',')) !== false) 
                    {
                        // Fetch related IDs
                        $entityID = DB::table('entity')->whereRaw('LOWER(entity_name) = ?', [strtolower($row[3])])->value('entity_id');
                        $schoolId = DB::table('school')->whereRaw('LOWER(school_name) = ?', [strtolower($row[4])])->value('school_id');
                        $courseId = DB::table('courses')->whereRaw('LOWER(course_name) = ?', [strtolower($row[5])])->value('course_id');
                        $programId = DB::table('program')->whereRaw('LOWER(program_name) = ?', [strtolower($row[6])])->value('program_id');
                        $convertedDate = Carbon::createFromFormat('d-m-Y', $row[10]);
                        // Check if the student already exists
                        $studentExists = DB::table('students')->whereRaw('LOWER(unique_id) = ?', [strtolower($row[7])])->exists();

                        if (!$studentExists) {
                            // Prepare user data
                            $userId = DB::table('users')->insertGetId([
                                'full_name' => $row[0],
                                'email' => $row[1],
                                'phone' => $row[2],
                                'fk_role_id' => $roleId,
                                'created_by' => $email,
                                'updated_by' => $email,
                                'created_date' => now(),
                                'updated_date' => now(),
                                'active' => 1,
                            ]);

                            // Prepare student data
                            $studentsData[] = [
                                'fk_entity_id' => $entityID,
                                'fk_school_id' => $schoolId,
                                'fk_course_id' => $courseId,
                                'fk_program_id' => $programId,
                                'fk_user_id' => $userId,
                                'unique_id' => $row[7],
                                'batch_code' => $row[8],
                                'semester_code' => $row[9],
                                'enrollment_date' => $convertedDate,
                                'created_by' => $email,
                                'updated_by' => $email,
                                'created_date' => now(),
                                'updated_date' => now(),
                                'active' => 1,
                            ];
                        }
                    }

                    // Batch insert student data
                    if (!empty($studentsData)) {
                        DB::table('students')->insert($studentsData);
                    }

                    fclose($handle);
                    $mesg = "File uploaded successfully";
                    return response()->json(['message' => $mesg]);
                } else {
                    $mesg = "Error in uploading file";
                    return response()->json(['message' => 'Error reading the file.']);
                }            
            }
            return response()->json(['message' => 'No file uploaded.'], 400);
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function DownloadStudentTemplate()
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            $filename = "StudentTemplate.csv";
            $campaignArray[] = array(
                'Full Name', 
                'Email ID', 
                'Contact No',
                'Entity',
                'School',
                'Course',
                'Program Type', 
                'Unique ID',
                'Batch Code', 
                'Semester', 
                'Enrollment Date');
            $campaignArray[] = array(
                'Full Name' => 'Arun Verma', 
                'Email ID' => 'arun.verma@test.com', 
                'Contact No' => '9876543210',
                'Entity' => 'AAFT Noida',
                'School' => 'School of Animation',
                'Course' => 'B.Sc. in Animation',
                'Program Type' => 'Degree', 
                'Unique ID' => 'AN_BAIS_1001',
                'Batch Code' => '234', 
                'Semester' => 3, 
                'Enrollment Date' => '2024-12-31');

            $csvContent = '';
            foreach ($campaignArray as $row)
            {
                $csvContent .= implode(',', $row) . "\n";
            }

            return Response::make($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        }
        else
        {
            return view('home.home-view');
        }
        
    }

    public function ViewStudentDetails(Request $req)
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            $entityId = $req->entity_id;
            $schoolId = $req->school_id;
            $courseId = $req->course_id;
            $optin = $req->optin;

            $userList = DB::select("
                SELECT *
                FROM (
                    SELECT 
                        u.user_id, 
                        e.entity_name, 
                        s.school_name, 
                        c.course_code, 
                        u.full_name, 
                        u.email, 
                        u.phone, 
                        sch.unique_id, 
                        sch.batch_code, 
                        sch.semester_code, 
                        sch.enrollment_date, 
                        u.`active`,
                        sch.fk_entity_id,
                        sch.fk_school_id,
                        sch.fk_course_id,
                        r.role_name, 
                        CASE 
                            WHEN EXISTS (
                                SELECT 1 FROM offline_questionarie oq WHERE oq.fk_user_id = u.user_id
                            ) OR EXISTS (
                                SELECT 1 FROM online_questionarie_yes oqy WHERE oqy.fk_user_id = u.user_id
                            ) THEN 'YES'
                            WHEN EXISTS (
                                SELECT 1 FROM questionarie_no qn WHERE qn.fk_user_id = u.user_id
                            ) THEN 'NO'
                            ELSE 'DID NOT FILL FORM'
                        END AS OPTIN
                    FROM users u
                    LEFT JOIN students sch ON u.user_id = sch.fk_user_id
                    LEFT JOIN entity e ON e.entity_id = sch.fk_entity_id
                    LEFT JOIN school s ON s.school_id = sch.fk_school_id
                    LEFT JOIN courses c ON c.course_id = sch.fk_course_id
                    LEFT JOIN program p ON p.program_id = sch.fk_program_id
                    LEFT JOIN role r ON r.role_id = u.fk_role_id
                    WHERE r.role_name = 'Student' AND u.`active` = 1
                ) subquery
                WHERE 
                    (? IS NULL OR OPTIN = ?)
                    AND (? IS NULL OR fk_entity_id = ?)
                    AND (? IS NULL OR fk_school_id = ?)
                    AND (? IS NULL OR fk_course_id = ?)
            ", [
                $optin, $optin,
                $entityId, $entityId,
                $schoolId, $schoolId,
                $courseId, $courseId
            ]);

            return response()->json(['userList' => $userList]);
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function DownloadStudentDetails(Request $req)
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            $entityId = $req->entity_id;
            $schoolId = $req->school_id;
            $courseId = $req->course_id;
            $optin = $req->optin;

            $userList = DB::select("
                SELECT *
                FROM (
                    SELECT 
                        u.user_id, 
                        e.entity_name, 
                        s.school_name, 
                        c.course_code, 
                        u.full_name, 
                        u.email, 
                        u.phone, 
                        sch.unique_id, 
                        sch.batch_code, 
                        sch.semester_code, 
                        sch.enrollment_date, 
                        u.`active`,
                        sch.fk_entity_id,
                        sch.fk_school_id,
                        sch.fk_course_id,
                        r.role_name, 
                        CASE 
                            WHEN EXISTS (
                                SELECT 1 FROM offline_questionarie oq WHERE oq.fk_user_id = u.user_id
                            ) OR EXISTS (
                                SELECT 1 FROM online_questionarie_yes oqy WHERE oqy.fk_user_id = u.user_id
                            ) THEN 'YES'
                            WHEN EXISTS (
                                SELECT 1 FROM questionarie_no qn WHERE qn.fk_user_id = u.user_id
                            ) THEN 'NO'
                            ELSE 'DID NOT FILL FORM'
                        END AS OPTIN
                    FROM users u
                    LEFT JOIN students sch ON u.user_id = sch.fk_user_id
                    LEFT JOIN entity e ON e.entity_id = sch.fk_entity_id
                    LEFT JOIN school s ON s.school_id = sch.fk_school_id
                    LEFT JOIN courses c ON c.course_id = sch.fk_course_id
                    LEFT JOIN program p ON p.program_id = sch.fk_program_id
                    LEFT JOIN role r ON r.role_id = u.fk_role_id
                    WHERE r.role_name = 'Student' AND u.`active` = 1
                ) subquery
                WHERE 
                    (? IS NULL OR OPTIN = ?)
                    AND (? IS NULL OR fk_entity_id = ?)
                    AND (? IS NULL OR fk_school_id = ?)
                    AND (? IS NULL OR fk_course_id = ?)
            ", [
                $optin, $optin,
                $entityId, $entityId,
                $schoolId, $schoolId,
                $courseId, $courseId
            ]);

            $filename = "StudentDetails.csv";
            $campaignArray[] = array('Entity', 'Unique ID','Name', 'Email ID', 'Contact No', 'School', 'Program Code', 'Batch Code', 'Semester', 'Enrollment Date', 'Optin');
            foreach($userList as $user)
            {
                $campaignArray[] = array(
                    'Entity' => $user->entity_name,
                    'Unique ID' => $user->unique_id,
                    'Name' => $user->full_name,
                    'Email ID' => $user->email,
                    'Contact No' => $user->phone,
                    'School' => $user->school_name,
                    'Program Code' => $user->course_code,
                    'Batch Code' => $user->batch_code,
                    'Semester' => $user->semester_code,
                    'Enrollment Date' => $user->enrollment_date,
                    'Optin' => $user->OPTIN
                );
            }
            $csvContent = '';
            foreach ($campaignArray as $row)
            {
                $csvContent .= implode(',', $row) . "\n";
            }

            return Response::make($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function AutoCompleteCompany(Request $req)
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            $companyName = $req->get('query');
            $companyNameList = DB::table('company_details')->where('comp_name', 'LIKE', "%{$companyName}%")->pluck('comp_name');
            return response()->json($companyNameList);
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function CheckCompany(Request $req)
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            $compName = $req->compName;
            $compId = DB::table('company_details')->whereRaw('LOWER(comp_name) = ?', [strtolower($compName)])->value('comp_id');
            
            if($compId != 0)
            {
                $compLeadList = DB::select("SELECT cd.comp_id, cd.comp_name, e.entity_name, s.school_name, c.course_name, p.program_name, u.email, ind.sector_name, l.industry_name, 
                                                    cld.resource_person, cld.designation, cld.primary_email, cld.primary_phone, cld.leadsource, cld.lead_stage, 
                                                    cld.industry_engagement FROM company_lead_details cld
                                            LEFT JOIN company_details cd ON cld.fk_comp_id = cd.comp_id
                                            LEFT JOIN entity e ON cld.fk_entity_id = e.entity_id
                                            LEFT JOIN school s ON cld.fk_school_id = s.school_id
                                            LEFT JOIN courses c ON cld.fk_course_id = c.course_id
                                            LEFT JOIN program p ON cld.fk_program_id = p.program_id
                                            LEFT JOIN users u ON cld.fk_spoc_id = u.user_id
                                            LEFT JOIN industry_sector ind ON cld.fk_industry_sector_id = ind.industry_sector_id
                                            LEFT JOIN industry_location l ON cld.fk_location_id = l.industry_loc_id
                                            WHERE cd.comp_id = ?", [$compId]);

                return response()->json(['compLeadList' => $compLeadList, 'compId' => $compId]);
            }
            else 
            {
                return response()->json(['compLeadList' => 'Company does not exist']);
            }
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function AddCompany(Request $req)
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            $companyName = $req->companyName;
            $numbers = "";
            $lastCompId = DB::table('company_details')->orderBy('comp_unique_id', 'desc')->value('comp_unique_id');

            if (!empty($lastCompId)) {
                // Extract numeric part from the lastCompId
                if (preg_match_all('/\d+/', $lastCompId, $matches)) {
                    $numbers = (int)$matches[0][0]; // Convert the numeric part to integer
                    $numbers += 1; // Increment the number
                }
            } else {
                $numbers = 1001; // Default starting ID
            }

            $uniqueId = "AAFT" . $numbers; // Concatenate prefix with number

            //Insert new company record into company_details table
            $compId = DB::table('company_details')->insertGetId([
                'comp_unique_id' => $uniqueId,
                'comp_name' => $companyName,
                'comp_category' => $req->companyCategory,
                'comp_website' => $req->companyWebsite,
                'comp_month' => $req->companyMonth,
                'comp_year' => $req->companyYear,
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);

            // $leadData = $leadData = [
            //     "CompanyType" => [
            //         "CompanyTypeName" => "Industry Alliances"
            //     ],
            //     "CompanyProperties" => [
            //         [
            //             "Attribute" => "CompanyName",
            //             "Value" => $companyName
            //         ],
            //         [
            //             "Attribute" => "CompanyNumber",
            //             "Value" => $uniqueId
            //         ],
            //         [
            //             "Attribute" => "Website",
            //             "Value" => $req->companyWebsite
            //         ],
            //         [
            //             "Attribute" => "Custom_12",
            //             "Value" => $req->companyCategory
            //         ],
            //         [
            //             "Attribute" => "Custom_11",
            //             "Value" => $req->companyMonth
            //         ],
            //         [
            //             "Attribute" => "Custom_10",
            //             "Value" => $req->companyYear    
            //         ],
            //         [
            //             "Attribute" => "Stage",
            //             "Value" => "Active"    
            //         ]
            //     ]
            // ];

            // $response = $this->client->post('CompanyManagement.svc/Company.Create', [
            //     'query' => [
            //         'accessKey' => $this->accessKey,
            //         'secretKey' => $this->secretKey,
            //     ],
            //     'headers' => [
            //         'Content-Type' => 'application/json',
            //     ],
            //     'json' => $leadData,
            // ]);

            return response()->json(['mesg' => json_decode($response->getBody(), true)]);
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function AddCompanyLead(Request $req)
    {
        if(session('username') != "" && session('role') == "CRO")
        {
        $compId = $req->compId;
        $entityId = $req->entityValId;
        $schoolId = $req->schoolValId;
        $courseId = $req->courseValId;
        $programTypeId = $req->programValId;
        $resourcePerson = $req->resourcePersonValId;
        $designation = $req->designationValId;
        $email = $req->emailValId;
        $phone = $req->phoneValId;
        $industrySector = $req->industrySectorValId;
        $industryLocation = $req->industryLocationValId;
        $leadSource = $req->leadSourceValId;
        $leadStage = $req->leadStageValId;
        $industryEngagementValId = $req->industryEngagementValId;
        $numbers = "";
        $month = "";
        $year = "";
        $spocId = DB::table('users')->where('email', session('username'))->value('user_id');
        $lastCompId = DB::table('company_lead_details')->orderBy('hr_unqiue_id', 'desc')->value('hr_unqiue_id');

        if (!empty($lastCompId)) {
            // Extract numeric part from the lastCompId
            if (preg_match_all('/\d+/', $lastCompId, $matches)) {
                $numbers = (int)$matches[0][0]; // Convert the numeric part to integer
                $numbers += 1; // Increment the number
            }
        } else {
            $numbers = 1001; // Default starting ID
        }

        $uniqueId = "HR" . $numbers; // Concatenate prefix with number
        
        DB::table("company_lead_details")->insert([
                'fk_comp_id' => $compId,
                'fk_entity_id' => $entityId,
                'fk_school_id' => $schoolId,
                'fk_course_id' => $courseId,
                'fk_program_id' => $programTypeId,
                'resource_person' => $resourcePerson,
                'designation' => $designation,
                'primary_email' => $email,
                'primary_phone' => $phone,
                'fk_industry_sector_id' => $industrySector,
                'fk_location_id' => $industryLocation,
                'leadsource' => $leadSource,
                'lead_stage' => $leadStage,
                'industry_engagement' => $industryEngagementValId,
                'fk_spoc_id' => $spocId,
                'hr_unqiue_id' => $uniqueId,
                'month' => $month,
                'year' => $year,
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);

            $companyName = DB::table('company_details')->where('comp_id', $compId)->value('comp_name');
            $entityName = DB::table('entity')->where('entity_id', $entityId)->value('entity_name');
            $schoolName = DB::table('school')->where('school_id', $schoolId)->value('school_name');
            $courseName = DB::table('courses')->where('course_id', $courseId)->value('course_name');
            $program = DB::table('program')->where('program_id', $programTypeId)->value('program_name');
            $industrySector = DB::table('industry_sector')->where('industry_sector_id', $industrySector)->value('sector_name');
            
            // Prepare lead data
            // $leadData = [
            //     [
            //     "Attribute" => "CompanyTypeName",
            //     "Value" => "Industry Alliances"
            //     ],
            //     [
            //     "Attribute" => "CompanyName",
            //     "Value" => $companyName
            //     ],
            //     [
            //     "Attribute" => "mx_Industry_Unique_ID",
            //     "Value" => $uniqueId
            //     ],
            //     [
            //     "Attribute" => "mx_AAFT_Entity",
            //     "Value" => $entityName
            //     ],
            //     [
            //     "Attribute" => "mx_AAFT_Noida_School",
            //     "Value" => $entityName == "AAFT Noida" ? $schoolName : ""
            //     ],
            //     [
            //     "Attribute" => "mx_AAFT_University_School",
            //     "Value" => $entityName == "AAFT University" ? $schoolName : ""
            //     ],
            //     [
            //     "Attribute" => "mx_AAFT_Online_School",
            //     "Value" => $entityName == "AAFT Online" ? $schoolName : ""
            //     ],
            //     [
            //         "Attribute" => "mx_AAFT_University_Course",
            //         "Value" => $entityName == "AAFT University" ? $courseName : ""
            //     ],
            //     [
            //         "Attribute" => "mx_AAFT_Noida_Course",
            //         "Value" => $entityName == "AAFT Noida" ? $courseName : ""
            //     ],
            //     [
            //         "Attribute" => "mx_AAFT_Online_Course",
            //         "Value" => $entityName == "AAFT Online" ? $courseName : ""
            //     ],
            //     [
            //         "Attribute" => "mx_Program_Type",
            //         "Value" => $program
            //     ],                
            //     [
            //         "Attribute" => "mx_Industry_Sector",
            //         "Value" => $industrySector
            //     ],
            //     [
            //         "Attribute" => "mx_City",
            //         "Value" => $req->industryLocationVal
            //     ],
            //     [
            //         "Attribute" => "FirstName",
            //         "Value" => $resourcePerson
            //     ],
            //     [
            //         "Attribute" => "JobTitle",
            //         "Value" => $designation
            //     ],
            //     [
            //         "Attribute" => "EmailAddress",
            //         "Value" => $email
            //     ],
            //     [
            //         "Attribute" => "Phone",
            //         "Value" => $phone
            //     ],
            //     [
            //         "Attribute" => "Source",
            //         "Value" => $leadSource
            //     ],
            //     [
            //         "Attribute" => "ProspectStage",
            //         "Value" => $leadStage
            //     ],
            //     [
            //         "Attribute" => "mx_Industry_Engagement_Mode",
            //         "Value" => $industryEngagementValId
            //     ],
            //     [
            //         "Attribute" => "mx_Month",
            //         "Value" => $month
            //     ],
            //     [
            //         "Attribute" => "mx_Year",
            //         "Value" => $year
            //     ],
            //     [
            //         "Attribute" => "CreatedByName",
            //         "Value" => "itservices+4@aaft.com"
            //     ]
            // ];

            // $leadData = [
            //     "CompanyTypeName" => "Industry Alliances",
            //     'Account' => $companyName,
            //     'mx_Industry_Unique_ID' => $uniqueId,
            //     'mx_AAFT_Entity' => $entityName,
            //     'mx_AAFT_Noida_School' => $entityName == "AAFT Noida" ? $schoolName : "",
            //     'mx_AAFT_University_School' => $entityName == "AAFT University" ? $schoolName : "",
            //     'mx_AAFT_Online_School' => $entityName == "AAFT Online" ? $schoolName : "",
            //     'mx_AAFT_University_Course' => $entityName == "AAFT University" ? $courseName : "",
            //     'mx_AAFT_Noida_Course' => $entityName == "AAFT Noida" ? $courseName : "",
            //     'mx_AAFT_Online_Course' => $entityName == "AAFT Online" ? $courseName : "",
            //     'mx_Program_Type' => $program,
            //     'OwnerId' => "itservices+4@aaft.com",
            //     'mx_Industry_Sector' => $industrySector,
            //     'mx_City' => $req->industryLocationVal,
            //     'FirstName' => $resourcePerson,
            //     'JobTitle' => $designation,
            //     'EmailAddress' => $email,
            //     'Phone' => $phone,
            //     'Source' => $leadSource,
            //     'ProspectStage' => $leadStage,
            //     'mx_Industry_Engagement_Mode' => $industryEngagementValId,
            //     'mx_Month' => $month,
            //     'mx_Year' => $year,
            //     // Add other fields as necessary
            // ];

            // $response = $this->client->post('LeadManagement.svc/Lead.Capture', [
            //     'query' => [
            //         'accessKey' => $this->accessKey,
            //         'secretKey' => $this->secretKey,
            //     ],
            //     'headers' => [
            //         'Content-Type' => 'application/json',
            //     ],
            //     'json' => $leadData,
            // ]);

            return response()->json(['mesg' => $companyName]);
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function CompanyReports()
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            //$user_id = DB::table('users')->where('email', session('username'))->value('user_id');
            $companyList = DB::select("SELECT DISTINCT 
                                        cd.comp_unique_id, 
                                        cd.comp_name, 
                                        cd.comp_category, 
                                        cd.comp_website, 
                                        cd.comp_month, 
                                        cd.comp_year,
                                        e.entity_name, 
                                        sch.school_name, 
                                        c.course_name, 
                                        p.program_name, 
                                        cld.resource_person, 
                                        cld.designation, 
                                        cld.primary_email, 
                                        cld.primary_phone,
                                        inds.sector_name, 
                                        indl.industry_name, 
                                        cld.leadsource, 
                                        cld.lead_stage, 
                                        cld.industry_engagement, 
                                        cld.hr_unqiue_id, 
                                        u.full_name 
                                    FROM company_details cd
                                    LEFT JOIN company_lead_details cld ON cd.comp_id = cld.fk_comp_id
                                    LEFT JOIN entity e ON cld.fk_entity_id = e.entity_id
                                    LEFT JOIN school sch ON cld.fk_school_id = sch.school_id
                                    LEFT JOIN courses c ON cld.fk_course_id = c.course_id
                                    LEFT JOIN program p ON cld.fk_program_id = p.program_id
                                    LEFT JOIN users u ON cld.fk_spoc_id = u.user_id
                                    LEFT JOIN industry_sector inds ON cld.fk_industry_sector_id = inds.industry_sector_id
                                    LEFT JOIN industry_location indl ON cld.fk_location_id = indl.industry_loc_id
                                    WHERE cd.active = 1 
                                    AND cd.created_by = ?", [session('username')]);
            
            $entityList = DB::select("SELECT * FROM entity");
            $programList = DB::select("SELECT * FROM program");

            return view('cro.company-report', compact(['companyList', 'entityList', 'programList']));
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function CompanyReportDetails(Request $req)
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            $entityID = $req->entity_id;
            $schoolID = $req->school_id;
            $courseID = $req->course_id;
            //$user_id = DB::table('users')->where('email', session('username'))->value('user_id');
            $companyList = DB::select("SELECT DISTINCT 
                                        cd.comp_unique_id, 
                                        cd.comp_name, 
                                        cd.comp_category, 
                                        cd.comp_website, 
                                        cd.comp_month, 
                                        cd.comp_year,
                                        e.entity_name, 
                                        sch.school_name, 
                                        c.course_name, 
                                        p.program_name, 
                                        cld.resource_person, 
                                        cld.designation, 
                                        cld.primary_email, 
                                        cld.primary_phone,
                                        inds.sector_name, 
                                        indl.industry_name, 
                                        cld.leadsource, 
                                        cld.lead_stage, 
                                        cld.industry_engagement, 
                                        cld.hr_unqiue_id, 
                                        u.full_name 
                                    FROM company_details cd
                                    LEFT JOIN company_lead_details cld ON cd.comp_id = cld.fk_comp_id
                                    LEFT JOIN entity e ON cld.fk_entity_id = e.entity_id
                                    LEFT JOIN school sch ON cld.fk_school_id = sch.school_id
                                    LEFT JOIN courses c ON cld.fk_course_id = c.course_id
                                    LEFT JOIN program p ON cld.fk_program_id = p.program_id
                                    LEFT JOIN users u ON cld.fk_spoc_id = u.user_id
                                    LEFT JOIN industry_sector inds ON cld.fk_industry_sector_id = inds.industry_sector_id
                                    LEFT JOIN industry_location indl ON cld.fk_location_id = indl.industry_loc_id
                                    WHERE cd.active = 1 
                                    AND cd.created_by = ? AND (? IS NULL OR cld.fk_entity_id = ?) AND (? IS NULL OR cld.fk_school_id = ?) AND (? IS NULL OR cld.fk_course_id = ?)", [session('username'), $entityID, $entityID, $schoolID, $schoolID, $courseID, $courseID]);
            
            return response()->json(['companyList' => $companyList]);
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function DownloadCompanyDetails(Request $req)
    {
        if(session('username') != "" && session('role') == "CRO")
        {
            $entityID = $req->entity_id;
            $schoolID = $req->school_id;
            $courseID = $req->course_id;
            $companyList = DB::select("SELECT DISTINCT 
                                        cd.comp_unique_id, 
                                        cd.comp_name, 
                                        cd.comp_category, 
                                        cd.comp_website, 
                                        cd.comp_month, 
                                        cd.comp_year,
                                        e.entity_name, 
                                        sch.school_name, 
                                        c.course_name, 
                                        p.program_name, 
                                        cld.resource_person, 
                                        cld.designation, 
                                        cld.primary_email, 
                                        cld.primary_phone,
                                        inds.sector_name, 
                                        indl.industry_name, 
                                        cld.leadsource, 
                                        cld.lead_stage, 
                                        cld.industry_engagement, 
                                        cld.hr_unqiue_id, 
                                        u.full_name 
                                    FROM company_details cd
                                    LEFT JOIN company_lead_details cld ON cd.comp_id = cld.fk_comp_id
                                    LEFT JOIN entity e ON cld.fk_entity_id = e.entity_id
                                    LEFT JOIN school sch ON cld.fk_school_id = sch.school_id
                                    LEFT JOIN courses c ON cld.fk_course_id = c.course_id
                                    LEFT JOIN program p ON cld.fk_program_id = p.program_id
                                    LEFT JOIN users u ON cld.fk_spoc_id = u.user_id
                                    LEFT JOIN industry_sector inds ON cld.fk_industry_sector_id = inds.industry_sector_id
                                    LEFT JOIN industry_location indl ON cld.fk_location_id = indl.industry_loc_id
                                    WHERE cd.active = 1 
                                    AND cd.created_by = ? AND (? IS NULL OR cld.fk_entity_id = ?) AND (? IS NULL OR cld.fk_school_id = ?) AND (? IS NULL OR cld.fk_course_id = ?)", [session('username'), $entityID, $entityID, $schoolID, $schoolID, $courseID, $courseID]);
            
            $filename = "CompanyDetails.csv";
            $campaignArray[] = array('Company Unique ID', 'Company Name','Category', 'Website', 'Entity', 'School', 'Course', 'Program Type', 'HR Unique ID', 'Designation', 'Email', 'Phone', 'Industry Sector', 'Location', 'Leadsource', 'Lead Stage', 'Industry Engagement');
            foreach($companyList as $user)
            {
                $campaignArray[] = array(
                    'Company Unique ID' => $user->comp_unique_id,
                    'Company Name' => $user->comp_name,
                    'Category' => $user->comp_category,
                    'Website' => $user->comp_website,
                    'Entity' => $user->entity_name,
                    'School' => $user->school_name,
                    'Course' => $user->course_name,
                    'Program Type' => $user->program_name,
                    'HR Unique ID' => $user->hr_unqiue_id,
                    'Designation' => $user->designation,
                    'Email' => $user->primary_email,
                    'Phone' => $user->primary_phone,
                    'Industry Sector' => $user->sector_name,
                    'Location' => $user->industry_name,
                    'Leadsource' => $user->leadsource,
                    'Lead Stage' => $user->lead_stage,
                    'Industry Engagement' =>$user->industry_engagement                
                );
            }
            $csvContent = '';
            foreach ($campaignArray as $row)
            {
                $csvContent .= implode(',', $row) . "\n";
            }

            return Response::make($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        }
        else
        {
            return view('home.home-view');
        }
    }

    public function SaveImage(Request $request)
    {
        $uploadedPaths = [];
        $folderPath = 'public/uploads/';

        // Check if the directory exists, if not, create it
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        // Check if files are uploaded
        if ($request->hasFile('studentFile')) {
            foreach ($request->file('studentFile') as $image) {
                // Generate a unique filename
                $filename = $image->getClientOriginalName();

                // Store the image
                $imagePath = $image->storeAs($folderPath, $filename);
                // Add the uploaded path to the response
                $uploadedPaths[] = str_replace('public/', 'storage/', $imagePath);
                // Extract the unique ID from the filename (if it exists)
                $name = pathinfo($filename, PATHINFO_FILENAME);

                // Find the user by unique_id
                $userId = DB::table('users')
                    ->join('students', 'users.user_id', '=', 'students.fk_user_id')
                    ->where('unique_id', '=', $name)
                    ->value('user_id');

                if ($userId) {
                    // Insert image details into the database
                    DB::table('user_image')->insert([
                        'img_name' => $filename,
                        'img_path' => str_replace('public/', 'storage', $imagePath),
                        'fk_user_id' => $userId,
                        'created_by' => session('username'),
                        'updated_by' => session('username'),
                        'created_date' => now(),
                        'updated_date' => now(),
                        'active' => 1
                    ]);

                    // Add the uploaded path to the response
                    $uploadedPaths[] = $folderPath . $filename;
                } else {
                    return response()->json([
                        'message' => "No user found with unique_id"
                    ]);
                }
            }

            // Return success response with uploaded paths
            return response()->json([
                'message' => 'Photos uploaded successfully!'
            ]);
        } else {
            // No files uploaded
            return response()->json([
                'message' => 'No file uploaded.'
            ], 400);
        }
    }

}
