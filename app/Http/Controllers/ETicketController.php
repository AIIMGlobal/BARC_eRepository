<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/* included models */
use App\Models\eTicket;
use App\Models\eTicketType;
use App\Models\eTicketFile;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Notification;

/* included mails */
use App\Mail\ETicketMail;
use App\Mail\ETicketStatusMail;

class ETicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_eTicket', $user)) {
            $types = eTicketType::where('status', 1)->get();
            $clients = User::where('user_type', 7)->where('status', 1)->orderBy('name_en')->get();

            if (Auth::user()->user_type == 7) {
                $projects = Project::where('client_id', Auth::id())->orderBy('name')->get();
            } else {
                $projects = Project::orderBy('name')->get();
            }
            
            $query = eTicket::query();

            if (isset($request->ticket_no) && $request->ticket_no != '') {
                $query->where('ticket_no', 'like', '%'.$request->ticket_no.'%');
            }

            if (isset($request->ticket_type_id) && $request->ticket_type_id != '') {
                $query->where('ticket_type_id', $request->ticket_type_id);
            }

            if (isset($request->client_id) && $request->client_id != '') {
                $query->where('client_id', $request->client_id);
            }

            if (isset($request->project_id) && $request->project_id != '') {
                $query->where('project_id', $request->project_id);
            }

            if (isset($request->priority) && $request->priority != '') {
                $query->where('priority', $request->priority);
            }

            if (isset($request->ticket_status) && $request->ticket_status != '') {
                $query->where('ticket_status', $request->ticket_status);
            }

            if ($request->from_date && $request->from_date != '') {
                $query->whereDate('created_at', '>=', $request->from_date);
            }
            
            if ($request->to_date && $request->to_date != '') {
                $query->whereDate('created_at', '<=', $request->to_date);
            }
            
            if (Auth::user()->user_type == 7) {
                $eTickets = $query->where('client_id', Auth::id())->latest()->paginate(20);
            } else {
                $eTickets = $query->latest()->paginate(20);
            }

            return view('backend.admin.eTicket.index', compact('eTickets', 'types', 'clients', 'projects'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (Gate::allows('add_eTicket', $user)) {
            $menu_expand = route('admin.eTicket.index');

            $types = eTicketType::where('status', 1)->get();
            $clients = User::where('user_type', 7)->where('status', 1)->orderBy('name_en')->get();

            if (Auth::user()->user_type == 7) {
                $projects = Project::where('client_id', Auth::id())->orderBy('name')->get();
            } else {
                $projects = Project::orderBy('name')->get();
            }

            return view('backend.admin.eTicket.create', compact('menu_expand', 'types', 'projects', 'clients'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (Gate::allows('add_eTicket', $user)) {
            if (Auth::user()->user_type == 7) {
                $this->validate($request, [
                    'ticket_type_id'    => 'required',
                    'project_id'        => 'required',
                    'title'             => 'required',
                    'priority'          => 'required',
                ]);
            } else {
                $this->validate($request, [
                    'ticket_type_id'    => 'required',
                    'client_id'         => 'required',
                    'project_id'        => 'required',
                    'title'             => 'required',
                    'priority'          => 'required',
                ]);
            }

            $ticketID = eTicket::select('ticket_no')->latest()->first();

            $eTicket = new eTicket;

            $eTicket->ticket_type_id    = $request->ticket_type_id;
            $eTicket->client_id         = $request->client_id;
            $eTicket->project_id        = $request->project_id;
            $eTicket->ticket_no         = $ticketID ? ($ticketID->ticket_no + 1) : 1;
            $eTicket->title             = $request->title;
            $eTicket->description       = $request->description;
            $eTicket->priority          = $request->priority;
            $eTicket->has_file          = $request->has_file ?? 0;
            $eTicket->status            = 0;
            $eTicket->created_by        = Auth::id();

            $eTicket->save();

            if ($request->has_file == 1) {
                foreach ($request->file as $key => $file) {
                    if ($request->file('file')[$key]) {
                        $path = $request->file('file')[$key]->store('/public/eTicket');
                        $path = Str::replace('public/eTicket', '', $path);
                        $documentFile = Str::replace('/', '', $path);

                        $document['ticket_id']  = $eTicket->id;
                        $document['file_name']  = $request->file[$key]->getClientOriginalName();
                        $document['file']       = $documentFile;
                        $document['created_by'] = Auth::id();

                        eTicketFile::create($document);
                    }
                }
            }

            $setting = Setting::first();
            $mailPerm = Permission::where('name_en', 'e_ticket_mail')->first();

            $notifyPerm = Permission::where('name_en', 'e_ticket_notification')->first();

            $mailPermSupport = Permission::where('name_en', 'e_ticket_support_mail')->first();

            $notifyPermSupport = Permission::where('name_en', 'e_ticket_support_notification')->first();

            $pmGeteTicket = Permission::where('name_en', 'pm_get_eticket_support')->first();

            $employeeGeteTicket = Permission::where('name_en', 'employee_get_eticket_support')->first();
            
            if ($notifyPerm) {
                $rolePermission = RolePermission::select('role_id')->where('permission_id', $notifyPerm->id)->groupBy('role_id')->get();
            } else {
                $rolePermission = [];
            }

            if ($mailPerm) {
                $rolePerm = RolePermission::select('role_id')->where('permission_id', $mailPerm->id)->groupBy('role_id')->get();
            } else {
                $rolePerm = [];
            }

            if ($notifyPermSupport) {
                $rolePermissionSupport = RolePermission::select('role_id')->where('permission_id', $notifyPermSupport->id)->groupBy('role_id')->get();
            } else {
                $rolePermissionSupport = [];
            }

            if ($mailPermSupport) {
                $rolePermSupport = RolePermission::select('role_id')->where('permission_id', $mailPermSupport->id)->groupBy('role_id')->get();
            } else {
                $rolePermSupport = [];
            }

            if ($pmGeteTicket) {
                $pmrolePermissionSupport = RolePermission::select('role_id')->where('permission_id', $pmGeteTicket->id)->groupBy('role_id')->get();
            } else {
                $pmrolePermissionSupport = [];
            }

            if ($employeeGeteTicket) {
                $employeerolePermSupport = RolePermission::select('role_id')->where('permission_id', $employeeGeteTicket->id)->groupBy('role_id')->get();
            } else {
                $employeerolePermSupport = [];
            }

            if ($rolePermissionSupport != []) {
                $admins = User::where('status', 1)->whereIn('role_id', $rolePermissionSupport)->get();
            
                foreach ($admins as $admin) {
                    $notification = new Notification;
            
                    $notification->type             = 21;
                    $notification->title            = "New E-Ticket Created";
                    $notification->message          = "New E-Ticket Created by ".($eTicket->client->name_en ?? '');
                    $notification->route_name       = route('admin.eTicket.show', Crypt::encryptString($eTicket->id));
                    $notification->sender_role_id   = $eTicket->client->role_id ?? '';
                    $notification->sender_user_id   = $eTicket->client->id ?? '';
                    $notification->receiver_role_id = $admin->role_id;
                    $notification->receiver_user_id = $admin->id;
        
                    $notification->save();
                }
            }

            if ($rolePermSupport != []) {
                $admins = User::where('status', 1)->whereIn('role_id', $rolePermSupport)->get();
                
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new ETicketMail($admin, $setting, $eTicket));
                }
            }

            if ($rolePermission != []) {
                if ($pmrolePermissionSupport != []) {
                    $admins = User::where('status', 1)->whereIn('role_id', $rolePermission)->where('id', ($eTicket->project->pm_id ?? 0))->get();
            
                    foreach ($admins as $admin) {
                        $notification = new Notification;
                
                        $notification->type             = 21;
                        $notification->title            = "New E-Ticket Created";
                        $notification->message          = "New E-Ticket Created by ".($eTicket->client->name_en ?? '');
                        $notification->route_name       = route('admin.eTicket.show', Crypt::encryptString($eTicket->id));
                        $notification->sender_role_id   = $eTicket->client->role_id ?? '';
                        $notification->sender_user_id   = $eTicket->client->id ?? '';
                        $notification->receiver_role_id = $admin->role_id;
                        $notification->receiver_user_id = $admin->id;
            
                        $notification->save();
                    }
                }

                if ($employeerolePermSupport != []) {
                    $assignees = Project::select('employee_ids')->where('id', $request->project_id)->first();

                    if ($assignees) {
                        $assigneeList = explode(',', $assignees->employee_ids);

                        $admins = User::where('status', 1)->whereIn('role_id', $rolePermission)->whereIn('id', $assigneeList)->get();
            
                        foreach ($admins as $admin) {
                            $notification = new Notification;
                    
                            $notification->type             = 21;
                            $notification->title            = "New E-Ticket Created";
                            $notification->message          = "New E-Ticket Created by ".($eTicket->client->name_en ?? '');
                            $notification->route_name       = route('admin.eTicket.show', Crypt::encryptString($eTicket->id));
                            $notification->sender_role_id   = $eTicket->client->role_id ?? '';
                            $notification->sender_user_id   = $eTicket->client->id ?? '';
                            $notification->receiver_role_id = $admin->role_id;
                            $notification->receiver_user_id = $admin->id;
                
                            $notification->save();
                        }
                    }
                }
            }

            if ($rolePerm != []) {
                if ($pmrolePermissionSupport != []) {
                    $admins = User::where('status', 1)->whereIn('role_id', $rolePerm)->where('id', ($eTicket->project->pm_id ?? 0))->get();
                
                    foreach ($admins as $admin) {
                        Mail::to($admin->email)->send(new ETicketMail($admin, $setting, $eTicket));
                    }
                }

                if ($employeerolePermSupport != []) {
                    $assignees = Project::select('employee_ids')->where('id', $request->project_id)->first();

                    if ($assignees) {
                        $assigneeList = explode(',', $assignees->employee_ids);

                        $admins = User::where('status', 1)->whereIn('role_id', $rolePerm)->whereIn('id', $assigneeList)->get();
                
                        foreach ($admins as $admin) {
                            Mail::to($admin->email)->send(new ETicketMail($admin, $setting, $eTicket));
                        }
                    }
                }
            }

            return redirect()->route('admin.eTicket.show', Crypt::encryptString($eTicket->id))->with('success', "New E-Ticket Created Successfully!");
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();

        if (Gate::allows('show_eTicket', $user)) {
            $menu_expand = route('admin.eTicket.index');

            $id = Crypt::decryptString($id);
            $eTicket = eTicket::where('id', $id)->first();
            $eTicketFiles = eTicketFile::where('ticket_id', $id)->get();

            return view('backend.admin.eTicket.show', compact('menu_expand', 'eTicket', 'eTicketFiles'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();

        if (Gate::allows('edit_eTicket', $user)) {
            $menu_expand = route('admin.eTicket.index');

            $id = Crypt::decryptString($id);
            $eTicket = eTicket::where('id', $id)->first();

            if (Auth::user()->user_type == 7) {
                $projects = Project::where('client_id', Auth::id())->orderBy('name')->get();
            } else {
                $projects = Project::orderBy('name')->get();
            }

            $types = eTicketType::where('status', 1)->get();
            $clients = User::where('user_type', 7)->where('status', 1)->orderBy('name_en')->get();

            return view('backend.admin.eTicket.edit', compact('menu_expand', 'eTicket', 'projects', 'types', 'clients'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (Gate::allows('edit_eTicket', $user)) {
            $id = Crypt::decryptString($id);
            $eTicket = eTicket::where('id', $id)->first();

            if ($eTicket->status != 0) {
                return redirect()->back()->with('error', "Your E-Ticket already been accepted!");
            } else {
                if (Auth::user()->user_type == 7) {
                    $this->validate($request, [
                        'ticket_type_id'    => 'required',
                        'project_id'        => 'required',
                        'title'             => 'required',
                        'priority'          => 'required',
                    ]);
                } else {
                    $this->validate($request, [
                        'ticket_type_id'    => 'required',
                        'client_id'         => 'required',
                        'project_id'        => 'required',
                        'title'             => 'required',
                        'priority'          => 'required',
                    ]);
                }
                
                $eTicket->ticket_type_id    = $request->ticket_type_id;
                $eTicket->client_id         = $request->client_id;
                $eTicket->project_id        = $request->project_id;
                $eTicket->title             = $request->title;
                $eTicket->description       = $request->description;
                $eTicket->priority          = $request->priority;
                $eTicket->has_file          = $request->has_file ?? 0;
                $eTicket->status            = 0;
                $eTicket->updated_by        = Auth::id();
    
                $eTicket->save();

                if ($request->has_file == 1) {
                    foreach ($request->file as $key => $file) {
                        if ($request->file('file')[$key]) {
                            $path = $request->file('file')[$key]->store('/public/eTicket');
                            $path = Str::replace('public/eTicket', '', $path);
                            $documentFile = Str::replace('/', '', $path);
    
                            $document['ticket_id']  = $eTicket->id;
                            $document['file_name']  = $request->file[$key]->getClientOriginalName();
                            $document['file']       = $documentFile;
                            $document['created_by'] = Auth::id();
    
                            eTicketFile::create($document);
                        }
                    }
                }

                $setting = Setting::first();
                $mailPerm = Permission::where('name_en', 'e_ticket_mail')->first();

                $notifyPerm = Permission::where('name_en', 'e_ticket_notification')->first();

                $mailPermSupport = Permission::where('name_en', 'e_ticket_support_mail')->first();

                $notifyPermSupport = Permission::where('name_en', 'e_ticket_support_notification')->first();

                $pmGeteTicket = Permission::where('name_en', 'pm_get_eticket_support')->first();

                $employeeGeteTicket = Permission::where('name_en', 'employee_get_eticket_support')->first();
                
                if ($notifyPerm) {
                    $rolePermission = RolePermission::select('role_id')->where('permission_id', $notifyPerm->id)->groupBy('role_id')->get();
                } else {
                    $rolePermission = [];
                }

                if ($mailPerm) {
                    $rolePerm = RolePermission::select('role_id')->where('permission_id', $mailPerm->id)->groupBy('role_id')->get();
                } else {
                    $rolePerm = [];
                }

                if ($notifyPermSupport) {
                    $rolePermissionSupport = RolePermission::select('role_id')->where('permission_id', $notifyPermSupport->id)->groupBy('role_id')->get();
                } else {
                    $rolePermissionSupport = [];
                }

                if ($mailPermSupport) {
                    $rolePermSupport = RolePermission::select('role_id')->where('permission_id', $mailPermSupport->id)->groupBy('role_id')->get();
                } else {
                    $rolePermSupport = [];
                }

                if ($pmGeteTicket) {
                    $pmrolePermissionSupport = RolePermission::select('role_id')->where('permission_id', $pmGeteTicket->id)->groupBy('role_id')->get();
                } else {
                    $pmrolePermissionSupport = [];
                }

                if ($employeeGeteTicket) {
                    $employeerolePermSupport = RolePermission::select('role_id')->where('permission_id', $employeeGeteTicket->id)->groupBy('role_id')->get();
                } else {
                    $employeerolePermSupport = [];
                }

                if ($rolePermissionSupport != []) {
                    $admins = User::where('status', 1)->whereIn('role_id', $rolePermissionSupport)->get();
                
                    foreach ($admins as $admin) {
                        $notification = new Notification;
                
                        $notification->type             = 21;
                        $notification->title            = "New E-Ticket Created";
                        $notification->message          = "New E-Ticket Created by ".($eTicket->client->name_en ?? '');
                        $notification->route_name       = route('admin.eTicket.show', Crypt::encryptString($eTicket->id));
                        $notification->sender_role_id   = $eTicket->client->role_id ?? '';
                        $notification->sender_user_id   = $eTicket->client->id ?? '';
                        $notification->receiver_role_id = $admin->role_id;
                        $notification->receiver_user_id = $admin->id;
            
                        $notification->save();
                    }
                }

                if ($rolePermSupport != []) {
                    $admins = User::where('status', 1)->whereIn('role_id', $rolePermSupport)->get();
                    
                    foreach ($admins as $admin) {
                        Mail::to($admin->email)->send(new ETicketMail($admin, $setting, $eTicket));
                    }
                }

                if ($rolePermission != []) {
                    if ($pmrolePermissionSupport != []) {
                        $admins = User::where('status', 1)->whereIn('role_id', $rolePermission)->where('id', ($eTicket->project->pm_id ?? 0))->get();
                
                        foreach ($admins as $admin) {
                            $notification = new Notification;
                    
                            $notification->type             = 21;
                            $notification->title            = "New E-Ticket Created";
                            $notification->message          = "New E-Ticket Created by ".($eTicket->client->name_en ?? '');
                            $notification->route_name       = route('admin.eTicket.show', Crypt::encryptString($eTicket->id));
                            $notification->sender_role_id   = $eTicket->client->role_id ?? '';
                            $notification->sender_user_id   = $eTicket->client->id ?? '';
                            $notification->receiver_role_id = $admin->role_id;
                            $notification->receiver_user_id = $admin->id;
                
                            $notification->save();
                        }
                    }

                    if ($employeerolePermSupport != []) {
                        $assignees = Project::select('employee_ids')->where('id', $request->project_id)->first();

                        if ($assignees) {
                            $assigneeList = explode(',', $assignees->employee_ids);

                            $admins = User::where('status', 1)->whereIn('role_id', $rolePermission)->whereIn('id', $assigneeList)->get();
                
                            foreach ($admins as $admin) {
                                $notification = new Notification;
                        
                                $notification->type             = 21;
                                $notification->title            = "New E-Ticket Created";
                                $notification->message          = "New E-Ticket Created by ".($eTicket->client->name_en ?? '');
                                $notification->route_name       = route('admin.eTicket.show', Crypt::encryptString($eTicket->id));
                                $notification->sender_role_id   = $eTicket->client->role_id ?? '';
                                $notification->sender_user_id   = $eTicket->client->id ?? '';
                                $notification->receiver_role_id = $admin->role_id;
                                $notification->receiver_user_id = $admin->id;
                    
                                $notification->save();
                            }
                        }
                    }
                }

                if ($rolePerm != []) {
                    if ($pmrolePermissionSupport != []) {
                        $admins = User::where('status', 1)->whereIn('role_id', $rolePerm)->where('id', ($eTicket->project->pm_id ?? 0))->get();
                    
                        foreach ($admins as $admin) {
                            Mail::to($admin->email)->send(new ETicketMail($admin, $setting, $eTicket));
                        }
                    }

                    if ($employeerolePermSupport != []) {
                        $assignees = Project::select('employee_ids')->where('id', $request->project_id)->first();

                        if ($assignees) {
                            $assigneeList = explode(',', $assignees->employee_ids);

                            $admins = User::where('status', 1)->whereIn('role_id', $rolePerm)->whereIn('id', $assigneeList)->get();
                    
                            foreach ($admins as $admin) {
                                Mail::to($admin->email)->send(new ETicketMail($admin, $setting, $eTicket));
                            }
                        }
                    }
                }
    
                return redirect()->back()->with('success', "E-Ticket updated successfully!");
            }
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (Gate::allows('delete_eTicket', $user)) {
            $id = Crypt::decryptString($id);
            eTicket::where('id', $id)->delete();

            return redirect()->route('admin.eTicket.index')->with('success', 'E-Ticket deleted successfully!');
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    public function deleteFile($id)
    {
        $user = Auth::user();

        if (Gate::allows('delete_eTicket', $user)) {
            $id = Crypt::decryptString($id);
            $eTicket = eTicketFile::where('id', $id)->first();

            $f = 'eTicket/' . $eTicket->file;
                    
            if ($f) {
                if(Storage::disk('public')->exists($f))
                {
                    Storage::disk('public')->delete($f);
                }
            }

            $eTicket->delete();

            return redirect()->back()->with('success', 'File deleted successfully!');
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $user = Auth::user();

        if (Gate::allows('change_eTicket_status', $user)) {
            $id = Crypt::decryptString($id);
            $eTicket = eTicket::where('id', $id)->first();

            $setting = Setting::first();

            if ($request->value == 1) {
                $eTicket->status        = $request->value;
                $eTicket->accepted_by   = Auth::id();
                $eTicket->accepted_at   = now();

                $eTicket->save();

                $notification = new Notification;
            
                $notification->type             = 22;
                $notification->title            = "E-Ticket Accepted";
                $notification->message          = "Your E-Ticket #".$eTicket->ticket_no." has been accepted.";
                $notification->route_name       = route('admin.eTicket.show', Crypt::encryptString($eTicket->id));
                $notification->sender_role_id   = $eTicket->acceptedBy->role_id ?? '';
                $notification->sender_user_id   = $eTicket->acceptedBy->id ?? '';
                $notification->receiver_role_id = $eTicket->client->role_id ?? '';
                $notification->receiver_user_id = $eTicket->client->id ?? '';
    
                $notification->save();

                Mail::to($eTicket->client->email ?? '')->send(new ETicketStatusMail($setting, $eTicket));

                return redirect()->back()->with('success', 'Ticket Accepted!');
            } else if ($request->value == 2) {
                $eTicket->status        = $request->value;
                $eTicket->remarks       = $request->remarks;
                $eTicket->solved_by     = Auth::id();
                $eTicket->solved_at     = now();

                $eTicket->save();

                $notification = new Notification;
            
                $notification->type             = 23;
                $notification->title            = "E-Ticket Solved";
                $notification->message          = "Your E-Ticket #".$eTicket->ticket_no." status changed to solved.";
                $notification->route_name       = route('admin.eTicket.show', Crypt::encryptString($eTicket->id));
                $notification->sender_role_id   = $eTicket->solvedBy->role_id ?? '';
                $notification->sender_user_id   = $eTicket->solvedBy->id ?? '';
                $notification->receiver_role_id = $eTicket->client->role_id ?? '';
                $notification->receiver_user_id = $eTicket->client->id ?? '';
    
                $notification->save();

                Mail::to($eTicket->client->email ?? '')->send(new ETicketStatusMail($setting, $eTicket));

                return redirect()->back()->with('success', 'Ticket status changed to solved!');
            } else if ($request->value == 3) {
                $eTicket->status          = $request->value;
                $eTicket->remarks         = $request->remarks;
                $eTicket->rejected_by     = Auth::id();
                $eTicket->rejected_at     = now();

                $eTicket->save();

                $notification = new Notification;
            
                $notification->type             = 24;
                $notification->title            = "E-Ticket Declined";
                $notification->message          = "Your E-Ticket #".$eTicket->ticket_no." has been declined.";
                $notification->route_name       = route('admin.eTicket.show', Crypt::encryptString($eTicket->id));
                $notification->sender_role_id   = $eTicket->rejectedBy->role_id ?? '';
                $notification->sender_user_id   = $eTicket->rejectedBy->id ?? '';
                $notification->receiver_role_id = $eTicket->client->role_id ?? '';
                $notification->receiver_user_id = $eTicket->client->id ?? '';
    
                $notification->save();

                Mail::to($eTicket->client->email ?? '')->send(new ETicketStatusMail($setting, $eTicket));

                return redirect()->back()->with('success', 'Ticket Declined!');
            }
        } else {
            return abort(403, "You don't have permission!");
        }
    }
}
