<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

/* included models */
use App\Models\UserInfo;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Office;
use App\Models\UserAddress;
use App\Models\AcademicExamForm;
use App\Models\Institute;
use App\Models\Board;
use App\Models\Duration;
use App\Models\Post;
use App\Models\AcademicRecord;
use App\Models\UserCompanyDoc;
use App\Models\UserCategory;
use App\Models\Setting;

/* included mails */
use App\Mail\UserAccountApproveToUserMail;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('user_management', $user)) {
            $query = User::with('userInfo');

            if ($request->filled('user_id')) {
                $query->where('id', $request->user_id);
            }
            
            $users = $query->where('role_id', '!=', 1)->whereNotIn('status', [3,5])->latest()->get();

            if ($request->ajax()) {
                $html = view('backend.admin.user.table', compact('users'))->render();

                return response()->json([
                    'success' => true,
                    'html' => $html,
                ]);
            }

            return view('backend.admin.user.index', compact('users'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function researcherIndex(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('user_management', $user)) {
            $query = User::with('userInfo');

            if ($request->filled('company_id')) {
                $query->where('company_id', $request->company_id);
            }
            
            $employees = $query->where('user_type', 3)->where('status', 1)->latest()->paginate(15);

            return view('backend.admin.user.index', compact('employees'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function archive_list(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('archive_list', $user)) {
            $employees_query = User::with('userInfo');

            if (($request->name != '') && ($request->name != '')) {
                $searchQuery = $request->name;

                $employees_query->where(function($query) use($searchQuery) {
                    $query->where('name_en', 'like', '%'.$searchQuery.'%')
                            ->orWhere('mobile', 'like', '%'.$searchQuery.'%')
                            ->orWhere('email', 'like', '%'.$searchQuery.'%');
                });
            }
            
            $employees = $employees_query->where('user_type', 3)->where('status', 2)->latest()->paginate(15);

            return view('backend.admin.user.archive_list', compact('employees'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function create()
    {
        $user = Auth::user();

        if (Gate::allows('add_user', $user)) {
            if ($user->role_id == 1) {
                $roles = Role::where('status', 1)->get();
            } else {
                $roles = Role::where('status', 1)->where('id', '!=', 1)->get();
            }

            $menu_expand = route('admin.user.index');

            $departments = Department::where('status', 1)->get();
            $designations = Designation::where('status', 1)->get();
            $offices = Office::where('status', 1)->get();
            $divisions = Division::where('status', 1)->get();
            $exam_forms = AcademicExamForm::where('status', 1)->orderBy('sl','ASC')->get();
            $institutes = Institute::where('status', 1)->get();
            $boards = Board::where('status', 1)->get();
            $durations = Duration::where('status', 1)->get();
            $posts = Post::where('status', 1)->get();
            $deputys = User::whereHas('userInfo', function($query2) {
                                $query2->where('designation_id', 12);
                            })->where('status', 1)->get();

            $user_categories = UserCategory::latest()->get();

            return view('backend.admin.user.create', compact(
                'menu_expand', 
                'departments', 
                'designations', 
                'offices', 
                'divisions', 
                // 'districts', 
                // 'upazilas', 
                'roles',
                'exam_forms',
                'institutes',
                'boards',
                'durations',
                'posts',
                'user_categories',
                'deputys',
            ));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            if (Gate::allows('add_user', $user)) {
                if ($request->user_type == 4) {
                    $this->validate($request, [
                        // 'name_bn' => 'required',
                        'name_en'               => 'required',
                        'user_type'             => 'required',
                        'mobile'                => 'unique:users|required',
                        'email'                 => 'unique:users|required',
                        'password'              => 'required',
                        'role_id'               => 'required',
                        // 'employee_id'           => 'required',
                        'department_id'         => 'required',
                        'designation'           => 'required',
                        'office_id'             => 'required',
                        // 'present_division_id'   => 'required',
                        // 'present_district_id'   => 'required',
                        // 'present_upazila_id'    => 'required',
                    ]);
                } else {
                    $this->validate($request, [
                        // 'name_bn' => 'required',
                        'name_en'               => 'required',
                        'user_type'             => 'required',
                        'mobile'                => 'unique:users|required',
                        'email'                 => 'unique:users|required',
                        'password'              => 'required',
                        'role_id'               => 'required',
                        // 'employee_id'           => 'required',
                        'department_id'         => 'required',
                        'designation_id'        => 'required',
                        'office_id'             => 'required',
                        // 'present_division_id'   => 'required',
                        // 'present_district_id'   => 'required',
                        // 'present_upazila_id'    => 'required',
                    ]);
                }

                $newUser = new User;

                // $newUser->name_bn           = $request->name_bn;
                $newUser->name_en           = $request->name_en;
                $newUser->email             = $request->email;
                $newUser->mobile            = $request->mobile;
                $newUser->user_type         = $request->user_type;
                $newUser->role_id           = $request->role_id;
                $newUser->status            = 1;
                $newUser->user_category_id  = $request->user_category_id;
                $newUser->password          = Hash::make($request->password);

                $newUser->save();

                $userAddress = new UserAddress;

                $userAddress->user_id                   = $newUser->id;
                $userAddress->present_division_id       = $request->present_division_id;
                $userAddress->present_district_id       = $request->present_district_id;
                $userAddress->present_upazila_id        = $request->present_upazila_id;
                $userAddress->present_address           = $request->present_village_road;
                $userAddress->present_post_office       = $request->present_post_office;
                $userAddress->present_post_code         = $request->present_post_code;
                $userAddress->permanent_division_id     = $request->same_as_present_address ? $request->present_division_id : $request->permanent_division_id;
                $userAddress->permanent_district_id     = $request->same_as_present_address ? $request->present_district_id : $request->permanent_district_id;
                $userAddress->permanent_upazila_id      = $request->same_as_present_address ? $request->present_upazila_id : $request->permanent_upazila_id;
                $userAddress->permanent_post_office     = $request->same_as_present_address ? $request->present_upazila_id : $request->permanent_upazila_id;
                $userAddress->permanent_post_code       = $request->same_as_present_address ? $request->present_post_code : $request->permanent_post_code;
                $userAddress->permanent_address         = $request->same_as_present_address ? $request->present_village_road : $request->permanent_village_road;
                $userAddress->same_as_present_address   = $request->same_as_present_address ? 1 : 0;

                $userAddress->save();

                $userInfo = new UserInfo;

                $userInfo->user_id              = $newUser->id;
                $userInfo->department_id        = $request->department_id;
                $userInfo->designation_id       = $request->designation_id;
                $userInfo->office_id            = $request->office_id;
                $userInfo->employee_id          = $request->employee_id;
                $userInfo->gender               = $request->gender;
                $userInfo->religion             = $request->religion;
                $userInfo->dob                  = $request->dob;
                $userInfo->nid_no               = $request->nid_no;
                $userInfo->driving_license_no   = $request->driving_license_no;
                $userInfo->passport_no          = $request->passport_no;
                $userInfo->marital_status       = $request->marital_status;
                $userInfo->birth_certificate_no = $request->birth_certificate_no;
                $userInfo->availablity          = $request->availablity ?? 0;
                $userInfo->created_by           = $user->id;
                $userInfo->visitor_organization = $request->visitor_organization;
                $userInfo->visitor_designation  = $request->visitor_designation;
                $userInfo->start                = $request->start;

                if ($request->have_company_document == 1) {
                    if ($request->document) {
                        foreach ($request->document as $key => $file) {
                            if ($request->file('document')[$key]) {
                                $path = $request->file('document')[$key]->store('/public/companyDocument');
                                $path = Str::replace('public/companyDocument', '', $path);
                                $companyDoc = Str::replace('/', '', $path);

                                $document['user_id'] = $newUser->id;
                                $document['document_title'] = $request->document[$key]->getClientOriginalName();
                                $document['document'] = $companyDoc;
                                $document['created_by'] = Auth::id();

                                UserCompanyDoc::create($document);
                            }
                        }
                    }
                }

                if ($request->image) {
                    $cp = $request->file('image');
                    $extension = strtolower($cp->getClientOriginalExtension());
                    $randomFileName = 'userImage'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                    Storage::disk('public')->put('userImages/'.$randomFileName, File::get($cp));

                    $userInfo->image = $randomFileName;
                }
                
                if ($request->signature) {
                    $cp = $request->file('signature');
                    $extension = strtolower($cp->getClientOriginalExtension());
                    $signature = 'signature'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                    Storage::disk('public')->put('signature/'.$signature, File::get($cp));

                    $userInfo->signature = $signature;
                }

                $userInfo->save();

                if ($request->academic_exam_form_id) {
                    foreach ($request->academic_exam_form_id as $key => $value) {
                        if (($request->academic_exam_form_id_data[$key] ?? 0) == 1) {
                            $data['name_en'] = $request->academic_exam_form_name[$key] ?? NULL;
                            $data['user_id'] = $newUser->id;
                            $data['academic_exam_form_id'] = $value;
                            $data['roll'] = $request->roll[$key] ?? NULL;
                            $data['pass_year'] = $request->pass_year[$key] ?? NULL;
                            $data['institute_id'] = $request->institute_id[$key] ?? NULL;
                            $data['institute_name'] = $request->institute_name[$key] ?? NULL;
                            $data['exam_id'] = $request->exam_id[$key] ?? NULL;
                            $data['exam_name'] = $request->exam_name[$key] ?? NULL;
                            $data['board_id'] = $request->board_id[$key] ?? NULL;
                            $data['board_name'] = $request->board_name[$key] ?? NULL;
                            $data['reg_no'] = $request->reg_no[$key] ?? NULL;
                            $data['subject_id'] = $request->subject_id[$key] ?? NULL;
                            $data['subject_name'] = $request->subject_name[$key] ?? NULL;
                            $data['result_type'] = $request->result_type[$key] ?? NULL;
                            $data['result'] = $request->result[$key] ?? NULL;
                            $data['duration_id'] = $request->duration_id[$key] ?? NULL;
                            $data['status'] = $request->academic_exam_form_id_data[$key] ?? 0;

                            if (($request->file('certificate_file')[$key] ?? NULL)) {
                                $path = $request->file('certificate_file')[$key]->store('/public/certificate_file');
                                $path = Str::replace('public/certificate_file', '', $path);
                                $documentFile = Str::replace('/', '', $path);
                                
                                $data['certificate_file'] = $documentFile;
                            } else {
                                $data['certificate_file'] = NULL;
                            }

                            AcademicRecord::create($data);
                        }
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'New User Created Successfully!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission!",
                ], 403);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred:' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $currentUser = Auth::user();
        $id = Crypt::decryptString($id);

        if (Gate::allows('edit_user', $currentUser)) {
            $employee = User::with('userInfo')->where('id', $id)->first();
            $userAddress = UserAddress::where('user_id', $id)->first();

            if ($currentUser->role_id == 1) {
                $roles = Role::where('status', 1)->get();
            }else{
                $roles = Role::where('status', 1)->where('id', '!=', 1)->get();
            }

            $menu_expand = route('admin.user.index');

            $departments = Department::where('status', 1)->get();
            $designations = Designation::where('status', 1)->get();
            $offices = Office::where('status', 1)->get();
            $divisions = Division::where('status', 1)->get();
            $institutes = Institute::where('status', 1)->get();
            $boards = Board::where('status', 1)->get();
            $durations = Duration::where('status', 1)->get();
            $posts = Post::where('status', 1)->get();

            $presentDistricts = District::where('division_id', ($userAddress->present_division_id ?? 0))->get();
            $presentUpazilas = Upazila::where('district_id', ($userAddress->present_district_id ?? 0))->get();

            $permanentDistricts = District::where('division_id', ($userAddress->permanent_division_id ?? 0))->get();
            $permanentUpazilas = Upazila::where('district_id', ($userAddress->permanent_district_id ?? 0))->get();

            $docs = UserCompanyDoc::where('user_id', $id)->get();

            $academic_record_foms = $employee->academicRecordDatas->pluck('academic_exam_form_id', 'academic_exam_form_id');

            if (count($academic_record_foms) > 0) {
                $exam_forms = AcademicExamForm::whereIn('id', $academic_record_foms)->orderBy('sl','ASC')->get();
            } else {
                $exam_forms = AcademicExamForm::where('status', 1)->orderBy('sl','ASC')->get();
            }

            $user_categories = UserCategory::latest()->get();

            $deputys = User::whereHas('userInfo', function($query2) {
                $query2->where('designation_id', 12);
            })->where('status', 1)->get();

            return view('backend.admin.user.edit', compact(
                'employee',
                'docs',
                'roles', 
                'menu_expand', 
                'departments', 
                'designations', 
                'offices',
                'divisions',
                'presentDistricts',
                'presentUpazilas',
                'permanentDistricts',
                'permanentUpazilas',
                'exam_forms',
                'institutes',
                'boards',
                'durations',
                'posts',
                'user_categories',
                'deputys',
            ));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $currentUser = Auth::user();
            $newUser = User::where('id', $request->user_id)->first();

            if (Gate::allows('edit_user', $currentUser)) {
                if ($request->user_type == 4) {
                    $this->validate($request, [
                        // 'name_bn' => 'required',
                        'name_en'               => 'required',
                        'user_type'             => 'required',
                        'mobile'                => 'required|unique:users,mobile,' . $newUser->id,
                        'email'                 => 'required|unique:users,email,' . $newUser->id,
                        'role_id'               => 'required',
                        // 'employee_id'           => 'required',
                        'department_id'         => 'required',
                        'designation'           => 'required',
                        'office_id'             => 'required',
                        // 'present_division_id'   => 'required',
                        // 'present_district_id'   => 'required',
                        // 'present_upazila_id'    => 'required',
                    ]);
                } else {
                    $this->validate($request, [
                        // 'name_bn' => 'required',
                        'name_en'               => 'required',
                        'user_type'             => 'required',
                        'mobile'                => 'unique:users|required,mobile,' . $newUser->id,
                        'email'                 => 'unique:users|required,email,' . $newUser->id,
                        'role_id'               => 'required',
                        // 'employee_id'           => 'required',
                        'department_id'         => 'required',
                        'designation_id'        => 'required',
                        'office_id'             => 'required',
                        // 'present_division_id'   => 'required',
                        // 'present_district_id'   => 'required',
                        // 'present_upazila_id'    => 'required',
                    ]);
                }

                // $newUser->name_bn            = $request->name_bn;
                $newUser->name_en           = $request->name_en;
                $newUser->email             = $request->email;
                $newUser->mobile            = $request->mobile;
                $newUser->role_id           = $request->role_id;
                $newUser->user_category_id  = $request->user_category_id;

                $newUser->save();

                $userAddress = UserAddress::where('user_id', $request->user_id)->first();

                if ($userAddress) {
                    $userAddress->user_id                   = $newUser->id;
                    $userAddress->present_division_id       = $request->present_division_id;
                    $userAddress->present_district_id       = $request->present_district_id;
                    $userAddress->present_upazila_id        = $request->present_upazila_id;
                    $userAddress->present_address           = $request->present_village_road;
                    $userAddress->present_post_office       = $request->present_post_office;
                    $userAddress->present_post_code         = $request->present_post_code;
                    $userAddress->permanent_division_id     = $request->same_as_present_address ? $request->present_division_id : $request->permanent_division_id;
                    $userAddress->permanent_district_id     = $request->same_as_present_address ? $request->present_district_id:$request->permanent_district_id;
                    $userAddress->permanent_upazila_id      = $request->same_as_present_address ? $request->present_upazila_id : $request->permanent_upazila_id;
                    $userAddress->permanent_post_office     = $request->same_as_present_address ? $request->present_post_office:$request->permanent_post_office;
                    $userAddress->permanent_post_code       = $request->same_as_present_address ? $request->present_post_code : $request->permanent_post_code;
                    $userAddress->permanent_address         = $request->same_as_present_address ? $request->present_village_road:$request->permanent_village_road;
                    $userAddress->same_as_present_address   = $request->same_as_present_address ? 1 : 0;
                } else {
                    $userAddress = new UserAddress;

                    $userAddress->user_id                   = $newUser->id;
                    $userAddress->present_division_id       = $request->present_division_id;
                    $userAddress->present_district_id       = $request->present_district_id;
                    $userAddress->present_upazila_id        = $request->present_upazila_id;
                    $userAddress->present_address           = $request->present_village_road;
                    $userAddress->present_post_office       = $request->present_post_office;
                    $userAddress->present_post_code         = $request->present_post_code;
                    $userAddress->permanent_division_id     = $request->same_as_present_address ? $request->present_division_id : $request->permanent_division_id;
                    $userAddress->permanent_district_id     = $request->same_as_present_address ? $request->present_district_id:$request->permanent_district_id;
                    $userAddress->permanent_upazila_id      = $request->same_as_present_address ? $request->present_upazila_id : $request->permanent_upazila_id;
                    $userAddress->permanent_post_office     = $request->same_as_present_address ? $request->present_post_office:$request->permanent_post_office;
                    $userAddress->permanent_post_code       = $request->same_as_present_address ? $request->present_post_code : $request->permanent_post_code;
                    $userAddress->permanent_address         = $request->same_as_present_address ? $request->present_village_road:$request->permanent_village_road;
                    $userAddress->same_as_present_address   = $request->same_as_present_address ? 1 : 0;
                }

                $userAddress->save();

                $userInfo = UserInfo::where('id', $request->user_info_id)->first();

                if ($userInfo) {
                    $userInfo->user_id              = $newUser->id;
                    $userInfo->department_id        = $request->department_id;
                    $userInfo->designation_id       = $request->designation_id;
                    $userInfo->designation          = $request->designation;
                    $userInfo->office_id            = $request->office_id;
                    $userInfo->employee_id          = $request->employee_id;
                    $userInfo->gender               = $request->gender;
                    $userInfo->dob                  = $request->dob;
                    $userInfo->nid_no               = $request->nid_no;
                    $userInfo->passport_no          = $request->passport_no;
                    $userInfo->marital_status       = $request->marital_status;
                    $userInfo->religion             = $request->religion;
                    $userInfo->driving_license_no   = $request->driving_license_no;
                    $userInfo->birth_certificate_no = $request->birth_certificate_no;
                    $userInfo->start                = $request->start;
                    // $userInfo->availablity          = $request->availablity ?? 0;
                    $userInfo->updated_by           = $currentUser->id;

                    if ($request->have_company_document == 1) {
                        if ($request->document) {
                            foreach ($request->document as $key => $file) {
                                if ($request->file('document')[$key]) {
                                    $path = $request->file('document')[$key]->store('/public/companyDocument');
                                    $path = Str::replace('public/companyDocument', '', $path);
                                    $companyDoc = Str::replace('/', '', $path);

                                    $document['user_id'] = $newUser->id;
                                    $document['document_title'] = $request->document[$key]->getClientOriginalName();
                                    $document['document'] = $companyDoc;
                                    $document['created_by'] = Auth::id();

                                    UserCompanyDoc::create($document);
                                }
                            }
                        }
                    }

                    if ($request->image) {
                        $imagePath = public_path(). '/storage/userImages/' . $userInfo->image;

                        if(($userInfo->image != '') || ($userInfo->image != NULL)) {
                            if(file_exists(public_path(). '/storage/userImages/' . $userInfo->image)){
                                unlink($imagePath);
                            }
                        }

                        $cp = $request->file('image');
                        $extension = strtolower($cp->getClientOriginalExtension());
                        $randomFileName = 'userImage'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                        Storage::disk('public')->put('userImages/' . $randomFileName, File::get($cp));

                        $userInfo->image = $randomFileName;
                    }

                    if ($request->signature) {
                        $signature_path = public_path(). '/storage/signature/' . $userInfo->signature;

                        if(($userInfo->signature != '') || ($userInfo->signature != NULL)) {
                            if(file_exists(public_path(). '/storage/signature/' . $userInfo->signature)){
                                unlink($signature_path);
                            }
                        }

                        $cp = $request->file('signature');
                        $extension = strtolower($cp->getClientOriginalExtension());
                        $signature = 'signature'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                        Storage::disk('public')->put('signature/'.$signature, File::get($cp));

                        $userInfo->signature = $signature;
                    }
                } else {
                    $userInfo = new UserInfo;

                    $userInfo->user_id              = $newUser->id;
                    $userInfo->department_id        = $request->department_id;
                    $userInfo->designation_id       = $request->designation_id;
                    $userInfo->designation          = $request->designation;
                    $userInfo->office_id            = $request->office_id;
                    $userInfo->employee_id          = $request->employee_id;
                    $userInfo->gender               = $request->gender;
                    $userInfo->dob                  = $request->dob;
                    $userInfo->nid_no               = $request->nid_no;
                    $userInfo->passport_no          = $request->passport_no;
                    $userInfo->marital_status       = $request->marital_status;
                    $userInfo->religion             = $request->religion;
                    $userInfo->driving_license_no   = $request->driving_license_no;
                    $userInfo->birth_certificate_no = $request->birth_certificate_no;
                    $userInfo->start                = $request->start;
                    // $userInfo->availablity          = $request->availablity ?? 0;
                    $userInfo->updated_by           = $currentUser->id;

                    if ($request->have_company_document == 1) {
                        if ($request->document) {
                            foreach ($request->document as $key => $file) {
                                if ($request->file('document')[$key]) {
                                    $path = $request->file('document')[$key]->store('/public/companyDocument');
                                    $path = Str::replace('public/companyDocument', '', $path);
                                    $companyDoc = Str::replace('/', '', $path);

                                    $document['user_id'] = $newUser->id;
                                    $document['document_title'] = $request->document[$key]->getClientOriginalName();
                                    $document['document'] = $companyDoc;
                                    $document['created_by'] = Auth::id();

                                    UserCompanyDoc::create($document);
                                }
                            }
                        }
                    }

                    if ($request->image) {
                        $imagePath = public_path(). '/storage/userImages/' . $userInfo->image;

                        if(($userInfo->image != '') || ($userInfo->image != NULL)) {
                            if(file_exists(public_path(). '/storage/userImages/' . $userInfo->image)){
                                unlink($imagePath);
                            }
                        }

                        $cp = $request->file('image');
                        $extension = strtolower($cp->getClientOriginalExtension());
                        $randomFileName = 'userImage'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                        Storage::disk('public')->put('userImages/' . $randomFileName, File::get($cp));

                        $userInfo->image = $randomFileName;
                    }

                    if ($request->signature) {
                        $signature_path = public_path(). '/storage/signature/' . $userInfo->signature;

                        if (($userInfo->signature != '') || ($userInfo->signature != NULL)) {
                            if(file_exists(public_path(). '/storage/signature/' . $userInfo->signature)){
                                unlink($signature_path);
                            }
                        }

                        $cp = $request->file('signature');
                        $extension = strtolower($cp->getClientOriginalExtension());
                        $signature = 'signature'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                        Storage::disk('public')->put('signature/'.$signature, File::get($cp));

                        $userInfo->signature = $signature;
                    }
                }

                $userInfo->save();

                if ($request->academic_exam_form_id) {
                    AcademicRecord::where('user_id', $newUser->id)->delete();

                    foreach ($request->academic_exam_form_id as $key => $value) {
                        if(($request->academic_exam_form_id_data[$key] ?? 0) == 1) {
                            $data['name_en'] = $request->academic_exam_form_name[$key] ?? NULL;
                            $data['user_id'] = $newUser->id;
                            $data['academic_exam_form_id'] = $value;
                            $data['roll'] = $request->roll[$key] ?? NULL;
                            $data['pass_year'] = $request->pass_year[$key] ?? NULL;
                            $data['institute_id'] = $request->institute_id[$key] ?? NULL;
                            $data['institute_name'] = $request->institute_name[$key] ?? NULL;
                            $data['exam_id'] = $request->exam_id[$key] ?? NULL;
                            $data['exam_name'] = $request->exam_name[$key] ?? NULL;
                            $data['board_id'] = $request->board_id[$key] ?? NULL;
                            $data['board_name'] = $request->board_name[$key] ?? NULL;
                            $data['reg_no'] = $request->reg_no[$key] ?? NULL;
                            $data['subject_id'] = $request->subject_id[$key] ?? NULL;
                            $data['subject_name'] = $request->subject_name[$key] ?? NULL;
                            $data['result_type'] = $request->result_type[$key] ?? NULL;
                            $data['result'] = $request->result[$key] ?? NULL;
                            $data['duration_id'] = $request->duration_id[$key] ?? NULL;
                            $data['status'] = $request->academic_exam_form_id_data[$key] ?? 0;

                            if ((isset($request->file('certificate_file')[$key]))) {
                                $path = $request->file('certificate_file')[$key]->store('/public/certificate_file');
                                $path = Str::replace('public/certificate_file', '', $path);
                                $documentFile = Str::replace('/', '', $path);
                                $data['certificate_file'] = $documentFile;
                            } else if(isset($request->old_certificate_file[$key])) {
                                
                                $data['certificate_file'] = $request->old_certificate_file[$key];
                            } else {
                                $data['certificate_file'] = NULL;
                            }

                            AcademicRecord::create($data);
                        }
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'User Information Updated Successfully!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission!",
                ], 403);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred:' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $currentUser = Auth::user();
        $id = Crypt::decryptString($id);

        if (Gate::allows('view_user', $currentUser)) {
            $menu_expand = route('admin.user.index');

            $employee = User::with('userInfo', 'userAddress')->where('id', $id)->first();
            $docs = UserCompanyDoc::where('user_id', $id)->get();

            return view('backend.admin.user.show', compact('menu_expand', 'employee', 'docs'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'oldPassword' => 'required',
            'newPassword' => 'required',
            'confirmPassword' => 'required',
        ]);

        $user = User::where('id', $request->id)->first();

        $oldPasswordInput = Hash::check($request->oldPassword, $user->password);

        if ($oldPasswordInput) {
            $newPassword        = $request->newPassword;
            $confirmPassword    = $request->confirmPassword;

            if ($newPassword === $confirmPassword) {
                $user->password = Hash::make($request->newPassword);

                $user->save();

                return redirect()->route('admin.edit_profile')->with('success', 'Password updated successfully..!');
            } else {
                return redirect()->route('admin.edit_profile')->with('error', 'New password and confirm password did not matched..!');
            }
        } else {
            return redirect()->route('admin.edit_profile')->with('error', 'Old password did not matched..!');
        }
    }

    public function changeOtherUserPassword(Request $request)
    {
        $this->validate($request, [
            'newPassword' => 'required',
            'confirmPassword' => 'required',
        ]);

        $user = User::where('id', $request->id)->first();
        
        $newPassword        = $request->newPassword;
        $confirmPassword    = $request->confirmPassword;

        if ($newPassword === $confirmPassword) {
            $user->password = Hash::make($request->newPassword);

            $user->save();

            return redirect()->route('admin.user.show', $user->id)->with('success', 'Password updated successfully..!');
        } else {
            return redirect()->route('admin.user.show', $user->id)->with('error', 'New password and confirm password did not matched..!');
        }
    }

    public function edit_profile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $employee = User::with('userInfo')->where('id', $user->id)->first();
        $divisions = Division::where('status', 1)->get();
        $userAddress = UserAddress::where('user_id', $user->id)->first();

        $presentDistricts = District::where('division_id', ($userAddress->present_division_id ?? 0) )->get();
        $presentUpazilas = Upazila::where('district_id', ($userAddress->present_district_id ?? 0) )->get();

        $permanentDistricts = District::where('division_id', ($userAddress->permanent_division_id ?? 0) )->get();
        $permanentUpazilas = Upazila::where('district_id', ($userAddress->permanent_district_id ?? 0) )->get();

        return view('backend.admin.user.edit_profile', compact(
            'user', 
            'employee', 
            'divisions', 
            'presentDistricts', 
            'presentUpazilas', 
            'permanentDistricts', 
            'permanentUpazilas',
        ));
    }

    public function update_profile(Request $request)
    {
        $userID = Auth::user()->id;

        $this->validate($request, [
            'name_en'               => 'required', 
            // 'name_bn'               => 'required',
            'email'                 => 'required|unique:users,email,'.$userID,
            'mobile'                => 'required|unique:users,mobile,'.$userID,
            // 'dob'                   => 'required',
            // 'gender'                => 'required',
            // 'religion'              => 'required',
            'employee_id'           => 'required',
            // 'marital_status'        => 'required',
            // 'present_division_id'   => 'required',
            // 'present_district_id'   => 'required',
            // 'present_upazila_id'    => 'required',
            // 'present_post_office'   => 'required',
            // 'present_post_code'     => 'required',
            // 'present_village_road'  => 'required',
        ]);

        DB::transaction(function () use ($request, $userID) {
            $user = User::where('id', Auth::user()->id)->first();

            $user->name_bn = $request->name_bn;
            $user->name_en = $request->name_en;

            // check email already exist on user update
            if ($request->email != $user->email) {
                $check = User::where('email', $request->email)->first();

                if (!empty($check)) {
                    return redirect()->route('admin.edit_profile')->with('error', 'Email already in use!');
                } else {
                    $user->email = $request->email;        
                }
            } else {
                $user->email = $request->email;
            }

            // check mobile already exist on user update
            if ($request->mobile != $user->mobile) {
                $checkMobile = User::where('mobile', $request->mobile)->first();

                if (!empty($checkMobile)) {
                    return redirect()->route('admin.edit_profile')->with('error', 'Mobile number already in use!');
                } else {
                    $user->mobile = $request->mobile;        
                }
            } else {
                $user->mobile = $request->mobile;
            }

            $user->save();

            $userInfo = UserInfo::where('user_id', $userID)->first();

            if ($userInfo) {
                $userInfo->dob                  = $request->dob;
                $userInfo->gender               = $request->gender;
                $userInfo->religion             = $request->religion;
                $userInfo->birth_certificate_no = $request->birth_certificate_no;
                $userInfo->nid_no               = $request->nid_no;
                $userInfo->employee_id          = $request->employee_id;
                $userInfo->passport_no          = $request->passport_no;
                $userInfo->driving_license_no   = $request->driving_license_no;
                $userInfo->marital_status       = $request->marital_status;

                if ($request->image) {
                    $imagePath = public_path(). '/storage/userImages/' . $userInfo->image;

                    if (($userInfo->image != '') || ($userInfo->image != NULL)) {
                        if (file_exists(public_path(). '/storage/userImages/' . $userInfo->image)) {
                            unlink($imagePath);
                        }
                    }

                    $cp = $request->file('image');
                    $extension = strtolower($cp->getClientOriginalExtension());
                    $randomFileName = 'userImage'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                    Storage::disk('public')->put('userImages/'.$randomFileName, File::get($cp));

                    $userInfo->image = $randomFileName;
                }

                if ($request->signature){
                    $signature_path = public_path(). '/storage/signature/' . $userInfo->signature;

                    if (($userInfo->signature != '') || ($userInfo->signature != NULL)) {
                        if(file_exists(public_path(). '/storage/signature/' . $userInfo->signature)){
                            unlink($signature_path);
                        }
                    }

                    $cp = $request->file('signature');
                    $extension = strtolower($cp->getClientOriginalExtension());
                    $signature = 'signature'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                    Storage::disk('public')->put('signature/'.$signature, File::get($cp));
                    $userInfo->signature = $signature;
                }

                $userInfo->save();
            } else {
                $userInfo = new UserInfo;

                $userInfo->user_id              = Auth::id();
                $userInfo->dob                  = $request->dob;
                $userInfo->gender               = $request->gender;
                $userInfo->religion             = $request->religion;
                $userInfo->birth_certificate_no = $request->birth_certificate_no;
                $userInfo->nid_no               = $request->nid_no;
                $userInfo->passport_no          = $request->passport_no;
                $userInfo->driving_license_no   = $request->driving_license_no;
                $userInfo->marital_status       = $request->marital_status;

                if ($request->image) {
                    $imagePath = public_path(). '/storage/userImages/' . $userInfo->image;

                    if (($userInfo->image != '') || ($userInfo->image != NULL)) {
                        if(file_exists(public_path(). '/storage/userImages/' . $userInfo->image)){
                            unlink($imagePath);
                        }
                    }

                    $cp = $request->file('image');
                    $extension = strtolower($cp->getClientOriginalExtension());
                    $randomFileName = 'userImage'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                    Storage::disk('public')->put('userImages/'.$randomFileName, File::get($cp));

                    $userInfo->image = $randomFileName;
                }

                if ($request->signature) {
                    $signature_path = public_path(). '/storage/signature/' . $userInfo->signature;

                    if(($userInfo->signature != '') || ($userInfo->signature != NULL)) {
                        if(file_exists(public_path(). '/storage/signature/' . $userInfo->signature)){
                            unlink($signature_path);
                        }
                    }

                    $cp = $request->file('signature');
                    $extension = strtolower($cp->getClientOriginalExtension());
                    $signature = 'signature'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                    Storage::disk('public')->put('signature/'.$signature, File::get($cp));

                    $userInfo->signature = $signature;
                }

                $userInfo->save();
            }

            $userAddress = UserAddress::where('user_id', $userID)->first();

            if ($userAddress) {
                $userAddress->present_division_id   = $request->present_division_id;
                $userAddress->present_district_id   = $request->present_district_id;
                $userAddress->present_upazila_id    = $request->present_upazila_id;
                $userAddress->present_post_office   = $request->present_post_office;
                $userAddress->present_post_code     = $request->present_post_code;
                $userAddress->present_address       = $request->present_village_road;
                
                if ($request->same_as_present_address == 1) {
                    $userAddress->permanent_division_id     = $request->present_division_id;
                    $userAddress->permanent_district_id     = $request->present_district_id;
                    $userAddress->permanent_upazila_id      = $request->present_upazila_id;
                    $userAddress->permanent_post_office     = $request->present_post_office;
                    $userAddress->permanent_post_code       = $request->present_post_code;
                    $userAddress->permanent_address         = $request->present_village_road;
                    $userAddress->same_as_present_address   = 1;
                } else {
                    $userAddress->permanent_division_id     = $request->permanent_division_id;
                    $userAddress->permanent_district_id     = $request->permanent_district_id;
                    $userAddress->permanent_upazila_id      = $request->permanent_upazila_id;
                    $userAddress->permanent_post_office     = $request->permanent_post_office;
                    $userAddress->permanent_post_code       = $request->permanent_post_code;
                    $userAddress->permanent_address         = $request->permanent_village_road;
                    $userAddress->same_as_present_address   = 0;
                }

                $userAddress->save();
            } else {
                $userAddress = new UserAddress;

                $userAddress->user_id               = Auth::id();
                $userAddress->present_division_id   = $request->present_division_id;
                $userAddress->present_district_id   = $request->present_district_id;
                $userAddress->present_upazila_id    = $request->present_upazila_id;
                $userAddress->present_post_office   = $request->present_post_office;
                $userAddress->present_post_code     = $request->present_post_code;
                $userAddress->present_address       = $request->present_village_road;
                
                if($request->same_as_present_address == 1){
                    $userAddress->permanent_division_id     = $request->present_division_id;
                    $userAddress->permanent_district_id     = $request->present_district_id;
                    $userAddress->permanent_upazila_id      = $request->present_upazila_id;
                    $userAddress->permanent_post_office     = $request->present_post_office;
                    $userAddress->permanent_post_code       = $request->present_post_code;
                    $userAddress->permanent_address         = $request->present_village_road;
                    $userAddress->same_as_present_address   = 1;
                }else{
                    $userAddress->permanent_division_id     = $request->permanent_division_id;
                    $userAddress->permanent_district_id     = $request->permanent_district_id;
                    $userAddress->permanent_upazila_id      = $request->permanent_upazila_id;
                    $userAddress->permanent_post_office     = $request->permanent_post_office;
                    $userAddress->permanent_post_code       = $request->permanent_post_code;
                    $userAddress->permanent_address         = $request->permanent_village_road;
                    $userAddress->same_as_present_address   = 0;
                }

                $userAddress->save();
            }
        });

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function editUserMinimum($id)
    {
        $user = User::where('id', $id)->first();
        $menu_expand = route('admin.user.index');
        $divisions = Division::where('status', 1)->get();
        return view('backend.admin.user.edit_user_minimum', compact('user', 'menu_expand', 'divisions'));
    }

    public function updateUserMinimum(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required', 
            'last_name' => 'required',
            'mobile' => 'required',
            'email' => 'required', 
            'address' => 'required', 
        ]);

        $user = User::where('id', $request->user_id)->first();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->full_name = $request->first_name.' '.$request->last_name;

        // check email already exist on user update
        if($request->email != $user->email){
            $check = User::where('email', $request->email)->first();
            if(!empty($check)){
                return redirect()->route('admin.user.index')->with('error', 'ই-মেইল অলরেডি ব্যাবহার করা হয়েছে..!');
            }else{
                $user->email = $request->email;        
            }
        }else{
            $user->email = $request->email;
        }

        $user->mobile = $request->mobile;
        $user->address = $request->address;
        $user->division_id = $request->division_id;
        $user->district_id = $request->district_id;
        $user->upazila_id = $request->upazila_id;
        $user->updated_by = Auth::user()->id;
        $user->status = $request->status;

        if($request->image){
            // $imageName = time().'.'.$request->image->extension();
            // $user->image = $imageName;
            // $request->image->move(public_path('userImages'), $imageName);
            $cp = $request->file('image');
            $extension = strtolower($cp->getClientOriginalExtension());
            $randomFileName = 'userImage'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
            Storage::disk('public')->put('userImages/'.$randomFileName, File::get($cp));
            $user->image = $randomFileName;
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully..!');

    }

    public function print_doc(Request $request,$id)
    {
        $employee = User::with('userInfo', 'userAddress')->where('id', $id)->first();

        $headers = array(

            "Content-type"=>"text/html",
    
            "Content-Disposition"=>"attachment;Filename=myGeneratefile.doc"
    
        );
        // return view('backend.admin.user.print_doc', compact('employee'));
        return \Response::make(view('backend.admin.user.print_doc', compact('employee')),200, $headers);
    
    }

    public function add_education($id)
    {
        $menu_expand = route('admin.user.index');
        $id = Crypt::decryptString($id);
        $currentUser = Auth::user();
        if(Gate::allows('add_educational_info', $currentUser)){
            $user = User::where('id', $id)->first();
            $exam_forms_query = AcademicExamForm::where('status', 1);
            if ($user->academicRecordInfo) {
                $ids = $user->academicRecordInfo->where('status','!=',2)->pluck('academic_exam_form_id');
                if (count($ids) > 0) {
                    $exam_forms_query->whereNotIn('id', $ids);
                }
            }
            $exam_forms = $exam_forms_query->orderBy('sl','ASC')->get();
            $institutes = Institute::where('status', 1)->get();
            $boards = Board::where('status', 1)->get();
            $durations = Duration::where('status', 1)->get();
            $posts = Post::where('status', 1)->get();
            return view('backend.admin.user.add_education', compact('user', 'menu_expand','exam_forms','institutes','boards','durations','posts'));
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function store_education(Request $request,$id)
    {
        // $menu_expand = route('admin.user.index');
        $id = Crypt::decryptString($id);
        $currentUser = Auth::user();
        if(Gate::allows('add_educational_info', $currentUser)){
            foreach ($request->academic_exam_form_id_data as $key => $value) {
                $data['name_en'] = $request->academic_exam_form_name[$key] ?? NULL;
                $data['user_id'] = $id;
                $data['academic_exam_form_id'] = $request->academic_exam_form_id[$key] ?? 0;
                $data['roll'] = $request->roll[$key] ?? NULL;
                $data['pass_year'] = $request->pass_year[$key] ?? NULL;
                $data['institute_id'] = $request->institute_id[$key] ?? NULL;
                $data['institute_name'] = $request->institute_name[$key] ?? NULL;
                $data['exam_id'] = $request->exam_id[$key] ?? NULL;
                $data['exam_name'] = $request->exam_name[$key] ?? NULL;
                $data['board_id'] = $request->board_id[$key] ?? NULL;
                $data['board_name'] = $request->board_name[$key] ?? NULL;
                $data['reg_no'] = $request->reg_no[$key] ?? NULL;
                $data['subject_id'] = $request->subject_id[$key] ?? NULL;
                $data['subject_name'] = $request->subject_name[$key] ?? NULL;
                $data['result_type'] = $request->result_type[$key] ?? NULL;
                $data['result'] = $request->result[$key] ?? NULL;
                $data['duration_id'] = $request->duration_id[$key] ?? NULL;
                $data['status'] = $request->academic_exam_form_id_data[$key] ?? 0;

                if (($request->file('certificate_file')[$key] ?? NULL)) {
                    $path = $request->file('certificate_file')[$key]->store('/public/certificate_file');
                    $path = Str::replace('public/certificate_file', '', $path);
                    $documentFile = Str::replace('/', '', $path);

                    
                    $data['certificate_file'] = $documentFile;
                } else {
                    $data['certificate_file'] = NULL;
                }

                AcademicRecord::create($data);
            }
            return redirect()->back()->with('success', 'Educational information updated successfully');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    public function companyDocDelete($id)
    {
        $id = Crypt::decryptString($id);
        $documentInfo = UserCompanyDoc::where('id', $id)->first();

        if ($documentInfo) {
            $imagePath = public_path(). '/storage/companyDocument/' . $documentInfo->document;

            if(($documentInfo->document != '') || ($documentInfo->document != NULL)) {
                if(file_exists(public_path(). '/storage/companyDocument/' . $documentInfo->document)){
                    unlink($imagePath);
                }
            }
        }

        $documentInfo->delete();

        return redirect()->back()->with('success', 'Document deleted.');
    }

    public function block(Request $request)
    {
        $user = Auth::user();

        if (!(Gate::allows('block_user', $user))) {
            return abort(403, "You don't have permission!");
        } else {
            $user = User::where('id', $request->id)->first();

            if ($user) {
                $user->status = 3;

                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'User Archived Successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User Not Found!'
                ]);
            }
        }
    }

    public function active($id)
    {
        $id = Crypt::decryptString($id);

        $currentUser = Auth::user();

        if(Gate::allows('block_user', $currentUser)){
            $user = User::where('id', $id)->first();
            $user->status = 1;
            $user->save();
            return redirect()->route('admin.user.index')->with('success', 'Employee Un-blocked successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        if (!(Gate::allows('delete_user', $user))) {
            return abort(403, "You don't have permission!");
        } else {
            $user = User::where('id', $request->id)->first();

            if ($user) {
                $user->status = 5;

                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'User Deleted Successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User Not Found!'
                ]);
            }
        }
    }

    public function approve(Request $request)
    {
        try {
            $authUser = Auth::user();

            if (!(Gate::allows('approve_user', $authUser))) {
                return abort(403, "You don't have permission!");
            } else {
                $setting = Setting::first();
                $user = User::where('id', $request->id)->first();

                if ($user) {
                    $user->status = 1;

                    $user->save();

                    Mail::to($user->email)->send(new UserAccountApproveToUserMail($setting, $user));

                    return response()->json([
                        'success' => true,
                        'message' => 'User Approved Successfully!'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'User Not Found!'
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return redirect()->route('login')->withErrors('Something went wrong');
        }
    }

    public function decline(Request $request)
    {
        $user = Auth::user();

        if (!(Gate::allows('decline_user', $user))) {
            return abort(403, "You don't have permission!");
        } else {
            $user = User::where('id', $request->id)->first();

            if ($user) {
                $user->status = 2;

                $user->save();

                $setting = Setting::first();

                Mail::to($user->email)->send(new UserAccountApproveToUserMail($setting, $user));

                return response()->json([
                    'success' => true,
                    'message' => 'User Declined Successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User Not Found!'
                ]);
            }
        }
    }
}
