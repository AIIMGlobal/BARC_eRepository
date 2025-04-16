<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

/* included models */
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\User;
use App\Models\ProjectInfo;
use App\Models\Document;
use App\Models\ProjectValue;
use App\Models\ProjectTransaction;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\Permission;
use App\Models\RolePermission;

/* included mails */
use App\Mail\PMAssignMail;
use App\Mail\ProjectAssignMail;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_project', $user)) {
            $query = Project::latest();

            if (isset($request->name) and $request->name != '') {
                $query->where('name', 'like', '%'.$request->name.'%');
            }
            
            if (!Gate::allows('can_view_all_project', $user)) {
                $query->whereRaw('FIND_IN_SET(?, employee_ids)', [$user->id])
                        ->orWhere('pm_id', $user->id);
            }
            
            $projects = $query->paginate(20);

            return view('backend.admin.project.index', compact('projects'));

        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function create()
    {
        $user = Auth::user();

        if (Gate::allows('add_project', $user)) {
            $menu_expand = route('admin.project.index');

            $project_categories = ProjectCategory::where('status', 1)->get();
            $clients = User::where('user_type', 7)->where('status', 1)->get();
            $employees = User::where('user_type', 3)->where('status', 1)->get();

            return view('backend.admin.project.create', compact('menu_expand', 'project_categories', 'clients', 'employees'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('add_project', $user)) {
            if (Gate::allows('hod_permission', $user)) {
                $this->validate($request, [
                    'name'              => 'required',
                    'pm_id'             => 'required',
                    'project_category'  => 'required',
                ]);
            } else {
                $this->validate($request, [
                    'name'              => 'required',
                    'project_category'  => 'required',
                    'client_id'         => 'required',
                    'pm_id'             => 'required',
                    'project_value'     => 'required',
                    'vat_tax'           => 'required',
                ]);
            }

            DB::transaction(function () use ($request, $user) {
                $project = new Project;

                $project->name            = $request->name;
                $project->start_date      = $request->start_date;
                $project->end_date        = $request->end_date;
                $project->category_id     = $request->project_category;
                $project->client_id       = $request->client_id;

                if ($request->employees) {
                    $project->employee_ids    = implode(',', $request->employees);
                }

                $project->pm_id           = $request->pm_id;
                $project->amount          = $request->project_value;
                $project->created_by      = $user->id;
                $project->status          = $request->status ?? 0;
                
                $project->save();

                $setting = Setting::first();

                if ($request->pm_id) {
                    $pm = User::where('status', 1)->where('id', $request->pm_id)->first();
                }

                if ($request->employees) {
                    $employees = User::where('status', 1)->whereIn('id', $request->employees)->get();
                }

                if ($request->employees) {
                    if (count($employees) > 0) {
                        foreach ($employees as $employee) {
                            if (Gate::allows('project_assign_mail', $employee)) {
                                Mail::to($employee->email)->send(new ProjectAssignMail($employee, $setting, $project));
                            }
                        }
                    }
                }

                if ($request->pm_id) {
                    if ($pm) {
                        if (Gate::allows('project_assign_mail', $pm)) {
                            Mail::to($pm->email)->send(new PMAssignMail($pm, $setting, $project));
                        }
                    }
                }

                if ($request->employees) {
                    if (count($employees) > 0) {
                        foreach ($employees as $employee) {
                            if (Gate::allows('project_assign_notification', $employee)) {
                                $notification = new Notification;
                    
                                $notification->type             = 4;
                                $notification->title            = "Project Assigned";
                                $notification->message          = "You have been assigned to ".($project->name)."project!";
                                $notification->route_name       = route('admin.project.show', Crypt::encryptString($project->id));
                                $notification->sender_role_id   = $project->createdBy->role_id ?? '';
                                $notification->sender_user_id   = $project->createdBy->id ?? '';
                                $notification->receiver_role_id = $employee->role_id;
                                $notification->receiver_user_id = $employee->id;
                    
                                $notification->save();
                            }
                        }
                    }
                }

                if ($request->pm_id) {
                    if ($pm) {
                        if (Gate::allows('project_assign_notification', $pm)) {
                            $notification = new Notification;
                    
                            $notification->type             = 4;
                            $notification->title            = "Project Assigned";
                            $notification->message          = "You have been assigned as project manager to ".($project->name)."project!";
                            $notification->route_name       = route('admin.project.show', Crypt::encryptString($project->id));
                            $notification->sender_role_id   = $project->createdBy->role_id ?? '';
                            $notification->sender_user_id   = $project->createdBy->id ?? '';
                            $notification->receiver_role_id = $pm->role_id;
                            $notification->receiver_user_id = $pm->id;
                
                            $notification->save();
                        } 
                    } 
                }

                $project_info['project_id'] = $project->id;
                $project_info['summery']    = $request->summery;

                ProjectInfo::create($project_info);

                $project_value['project_id']     = $project->id;
                $project_value['project_value']  = $request->project_value;
                $project_value['vat_tax']        = $request->vat_tax;
                $project_value['remarks']        = $request->remarks;
                $project_value['created_by']     = $user->id;
                $project_value['status']         = $project->status;

                ProjectValue::create($project_value);
                
                if ($request->have_document == 1) {
                    if ($request->file) {
                        foreach ($request->file as $key => $file) {
                            if ($request->file('file')[$key]) {
                                $path = $request->file('file')[$key]->store('/public/document');
                                $path = Str::replace('public/document', '', $path);
                                $appraisalimage = Str::replace('/', '', $path);

                                $document['name'] = $request->file[$key]->getClientOriginalName();
                                $document['file'] = $appraisalimage;
                                $document['type'] = 1;
                                $document['project_id'] = $project->id;
                                
                                if ($request->employees) {
                                    $document['user_ids'] = implode(',', $request->employees);
                                }

                                Document::create($document);
                            }
                        }
                    }
                }

                if ($request->has_projectTransaction == 1) {
                    $type       = $request->input('type', []);
                    $purpose    = $request->input('purpose', []);
                    $amount     = $request->input('transactionAmount', []);
                    $document   = $request->input('document', []);

                    $transactionInfo    = [];

                    foreach ($request->type as $index => $unit) {
                        $attach = NULL;

                        if ($request->file('document')[$index] ?? '') {
                            $path = $request->file('document')[$index]->store('/public/transactionDocument');

                            $path = Str::replace('public/transactionDocument', '', $path);

                            $attach = Str::replace('/', '', $path);
                        }

                        $transactionInfo[] = [
                            "project_id"    => $project->id,
                            "type"          => $type[$index],
                            "purpose"       => $purpose[$index],
                            "amount"        => $amount[$index],
                            "document"      => $attach ?? NULL,
                            "created_by"    => $user->id,
                            "created_at"    => date('Y-m-d'),
                            "updated_at"    => date('Y-m-d'),
                        ];
                    }

                    ProjectTransaction::insert($transactionInfo);
                }
            });

            return redirect()->route('admin.project.index')->with('success', 'Project added successfully!');
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        if (Gate::allows('view_project', $user)) {
            $menu_expand = route('admin.project.index');

            $id = Crypt::decryptString($id);
            $project = Project::where('id', $id)->first();
            $project_categories = ProjectCategory::where('status',1)->get();
            $clients = User::where('user_type', 7)->where('status', 1)->get();
            $employees = User::where('user_type', 3)->where('status', 1)->get();
            $projectEarnings = ProjectTransaction::where('project_id', $id)->where('type', 1)->get();
            $projectExpenses = ProjectTransaction::where('project_id', $id)->where('type', 2)->get();
            $project_value = ProjectValue::where('project_id', $id)->first();

            return view('backend.admin.project.view', compact('menu_expand', 'project', 'project_categories', 'clients', 'employees', 'project_value', 'projectEarnings', 'projectExpenses'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function edit($id)
    {
        $user = Auth::user();

        if (Gate::allows('edit_project', $user)) {
            $menu_expand = route('admin.project.index');

            $id = Crypt::decryptString($id);
            $project = Project::where('id', $id)->first();
            $project_categories = ProjectCategory::where('status',1)->get();
            $clients = User::where('user_type', 7)->where('status', 1)->get();
            $employees = User::where('user_type', 3)->where('status', 1)->get();
            $project_value = ProjectValue::where('project_id', $id)->first();
            $projectTransactions = ProjectTransaction::where('project_id', $id)->get();

            return view('backend.admin.project.edit', compact('menu_expand', 'project', 'project_categories', 'clients','employees', 'project_value', 'projectTransactions'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (Gate::allows('edit_project', $user)) {
            if (Gate::allows('hod_permission', $user)) {
                $this->validate($request, [
                    'name'              => 'required',
                    'pm_id'             => 'required',
                    'project_category'  => 'required',
                ]);
            } else {
                $this->validate($request, [
                    'name'              => 'required',
                    'project_category'  => 'required',
                    'client_id'         => 'required',
                    'pm_id'             => 'required',
                    'project_value'     => 'required',
                    'vat_tax'           => 'required',
                ]);
            }

            DB::transaction(function () use ($request, $user, $id) {
                $project = Project::where('id', $id)->first();

                $existPM = $project->pm_id;

                if ($project->employee_ids) {
                    $eAssignees = explode(',', $project->employee_ids);
                }

                $project->name            = $request->name;
                $project->start_date      = $request->start_date;
                $project->end_date        = $request->end_date;
                $project->category_id     = $request->project_category;
                $project->client_id       = $request->client_id;

                if ($request->employees) {
                    $project->employee_ids    = implode(',', $request->employees);
                }

                $project->pm_id           = $request->pm_id;
                $project->amount          = $request->project_value;
                $project->updated_by      = $user->id;
                $project->status          = $request->status ?? 0; 

                $project->save();

                $setting = Setting::first();

                $mailPerm = Permission::where('name_en', 'project_assign_mail')->first();

                if ($mailPerm) {
                    $rolePerm = RolePermission::select('role_id')->where('permission_id', $mailPerm->id)->groupBy('role_id')->get();
                } else {
                    $rolePerm = [];
                }

                if ($rolePerm != []) {
                    $pm = User::where('status', 1)->where('id', $request->pm_id)->whereIn('role_id', $rolePerm)->first();

                    if ($request->employees) {
                        $employees = User::where('status', 1)->whereIn('id', $request->employees)->whereNotIn('id', ($eAssignees ?? []))->whereIn('role_id', $rolePerm)->get();

                        if (count($employees) > 0) {
                            foreach ($employees as $employee) {
                                Mail::to($employee->email)->send(new ProjectAssignMail($employee, $setting, $project));
                            }
                        }
                    }

                    if ($pm) {
                        if ($existPM != $pm->id) {
                            Mail::to($pm->email)->send(new PMAssignMail($pm, $setting, $project));
                        }
                    }
                }

                $notifyPerm = Permission::where('name_en', 'project_assign_notification')->first();
            
                if ($notifyPerm) {
                    $rolePermission = RolePermission::select('role_id')->where('permission_id', $notifyPerm->id)->groupBy('role_id')->get();
                } else {
                    $rolePermission = [];
                }

                if ($rolePermission != []) {
                    $pm = User::where('status', 1)->where('id', $request->pm_id)->whereIn('role_id', $rolePermission)->first();
                    
                    if ($request->employees) {
                        $employees = User::where('status', 1)->whereIn('id', $request->employees)->whereNotIn('id', ($eAssignees ?? []))->whereIn('role_id', $rolePermission)->get();

                        if (count($employees) > 0) {
                            foreach ($employees as $employee) {
                                $notification = new Notification;
                        
                                $notification->type             = 4;
                                $notification->title            = "Project Assigned";
                                $notification->message          = "You have been assigned to ".($project->name)."project!";
                                $notification->route_name       = route('admin.project.show', Crypt::encryptString($project->id));
                                $notification->sender_role_id   = $project->createdBy->role_id ?? '';
                                $notification->sender_user_id   = $project->createdBy->id ?? '';
                                $notification->receiver_role_id = $employee->role_id;
                                $notification->receiver_user_id = $employee->id;
                    
                                $notification->save();
                            }
                        }
                    }

                    if ($pm) {
                        if ($existPM != $pm->id) {
                            $notification = new Notification;
                    
                            $notification->type             = 4;
                            $notification->title            = "Project Assigned";
                            $notification->message          = "You have been assigned as project manager to ".($project->name)."project!";
                            $notification->route_name       = route('admin.project.show', Crypt::encryptString($project->id));
                            $notification->sender_role_id   = $project->createdBy->role_id ?? '';
                            $notification->sender_user_id   = $project->createdBy->id ?? '';
                            $notification->receiver_role_id = $pm->role_id;
                            $notification->receiver_user_id = $pm->id;
                
                            $notification->save();
                        }
                    }
                }

                $project_info = ProjectInfo::where('project_id', $id)->first();

                if ($project_info) {
                    $project_info->project_id = $id;
                    $project_info->summery    = $request->summery;
                } else {
                    $project_info = new ProjectInfo;

                    $project_info->project_id = $id;
                    $project_info->summery    = $request->summery;
                }

                $project_info->save();

                $project_value = ProjectValue::where('project_id', $id)->first();

                if ($project_value) {
                    $project_value->project_id    = $id;
                    $project_value->project_value = $request->project_value;
                    $project_value->vat_tax       = $request->vat_tax;
                    $project_value->remarks       = $request->remarks;
                    $project_value->status        = $request->status ?? 0;
                    $project_value->updated_by    = $user->id;
                } else {
                    $project_value = new ProjectValue;

                    $project_value->project_id    = $id;
                    $project_value->project_value = $request->project_value;
                    $project_value->vat_tax       = $request->vat_tax;
                    $project_value->remarks       = $request->remarks;
                    $project_value->status        = $request->status ?? 0;
                    $project_value->updated_by    = $user->id;
                }
                
                $project_value->save();
            });

            return redirect()->route('admin.project.index')->with('success', 'Project updated.');
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function delete($id)
    {
        $id = Crypt::decryptString($id);

        $project = Project::where('id', $id)->first();

        if ($project->status == 0) {
            $project->status = 1;
        } else {
            $project->status = 0;
        }

        $project->save();

        return redirect()->back()->with('success', 'Project status changed successfully!');
    }
}
