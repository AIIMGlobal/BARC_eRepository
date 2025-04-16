<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/* included models */
use App\Models\Leave;
use App\Models\LeaveCategory;
use App\Models\User;
use App\Models\Setting;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Notification;

/* included mails */
use App\Mail\LeaveMail;
use App\Mail\LeaveStatusMail;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_leave', $user)) {
            $categorys = LeaveCategory::where('status', 1)->get();
            $employees = User::where('user_type', 3)->where('status', 1)->get();

            $query = Leave::latest();

            if (isset($request->leave_category_id) && $request->leave_category_id != '') {
                $query->where('leave_category_id', $request->leave_category_id);
            }

            if (isset($request->user_id) && $request->user_id != '') {
                $query->where('user_id', $request->user_id);
            }

            if ($request->from_date && $request->from_date != '') {
                $query->whereDate('from_date', '>=', $request->from_date);
            } else {
                $query->whereYear('from_date', date('Y'));
            }
            
            if ($request->to_date && $request->to_date != '') {
                $query->whereDate('to_date', '<=', $request->to_date);
            } else {
                $query->whereYear('to_date', date('Y'));
            }
            
            if (Auth::user()->can('access_all_leave_list')) {
                $leaves = $query->paginate(20);
            } else {
                $leaves = $query->where('user_id', Auth::id())->paginate(20);
            }

            return view('backend.admin.leave.index', compact('leaves', 'categorys', 'employees'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    public function leaveSummary(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('leave_summary', $user)) {
            $categorys = LeaveCategory::where('status', 1)->get();
            $employees = User::where('user_type', 3)->where('status', 1)->get();

            $query = User::where('user_type', 3)->where('status', 1);

            if (isset($request->employee_id) and $request->employee_id != '') {
                $query->whereHas('userInfo', function($query2) use($request) {
                    $query2->where('employee_id', $request->employee_id);
                });
            }

            if (isset($request->user_id) && $request->user_id != '') {
                $query->where('id', $request->user_id);
            }
            
            $leaves = $query->get();

            return view('backend.admin.leave.leaveSummary', compact('leaves', 'categorys', 'employees'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    public function leaveSummaryMonthwise(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('leave_summary', $user)) {
            $categorys = LeaveCategory::where('status', 1)->get();
            $employees = User::where('user_type', 3)->where('status', 1)->get();

            $query = User::where('user_type', 3)->where('status', 1);

            if (isset($request->employee_id) and $request->employee_id != '') {
                $query->whereHas('userInfo', function($query2) use($request) {
                    $query2->where('employee_id', $request->employee_id);
                });
            }

            if (isset($request->user_id) && $request->user_id != '') {
                $query->where('id', $request->user_id);
            }

            if (isset($request->year) && $request->year != '') {
                $query->whereHas('leave', function($query2) use($request) {
                    $query2->whereYear('from_date', $request->year);
                });
            }
            
            $leaves = $query->get();

            return view('backend.admin.leave.leaveSummaryMonthwise', compact('leaves', 'categorys', 'employees'));
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

        if (Gate::allows('add_leave', $user)) {
            $menu_expand = route('admin.leave.index');

            $categorys = LeaveCategory::where('status', 1)->get();
            $employees = User::where('user_type', 3)->where('status', 1)->get();

            return view('backend.admin.leave.create', compact('menu_expand', 'categorys', 'employees'));
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
        
        if (Gate::allows('add_leave', $user)) {
            $this->validate($request, [
                'user_id'           => 'required',
                'leave_category_id' => 'required',
                'from_date'         => 'required',
                'to_date'           => 'required',
                'day_count'         => 'required',
                'reason'            => 'required',
            ]);

            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $checkSame = Leave::where('user_id', $request->user_id)
                                ->where(function($query) use ($from_date, $to_date){
                                    $query->where('from_date', $from_date)
                                            ->orWhere('to_date', $to_date);
                                })->first();

            if (!$checkSame) {
                $leave =  new Leave;

                $leave->user_id             = $request->user_id;
                $leave->leave_category_id   = $request->leave_category_id;
                $leave->from_date           = $request->from_date;
                $leave->to_date             = $request->to_date;
                $leave->day_count           = $request->day_count;
                $leave->reason              = $request->reason;
                $leave->status              = 0;
                $leave->created_by          = Auth::id();

                if ($request->leave_document) {
                    $cp = $request->file('leave_document');
                    $extension = strtolower($cp->getClientOriginalExtension());
                    $randomFileName = 'leave_document'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                    Storage::disk('public')->put('leaveDocument/' . $randomFileName, File::get($cp));

                    $leave->leave_document = $randomFileName;
                }

                $leave->save();

                $setting = Setting::first();

                if (($leave->employee->userInfo->designation_id ?? '') != 12) {
                    $admin = User::whereHas('userInfo', function($query2) {
                                        $query2->where('designation_id', 12);
                                    })->where('id', ($leave->employee->team_id ?? ''))->where('status', 1)->first();

                    if ($admin && (Auth::user()->user_type == 3)) {
                        $notification = new Notification;
                
                        $notification->type             = 1;
                        $notification->title            = "New Leave Application";
                        $notification->message          = "New Leave Application submitted by ".($leave->employee->name_en ?? '');
                        $notification->route_name       = route('admin.leave.show', Crypt::encryptString($leave->id));
                        $notification->sender_role_id   = $leave->employee->role_id ?? '';
                        $notification->sender_user_id   = $leave->user_id;
                        $notification->receiver_role_id = $admin->role_id;
                        $notification->receiver_user_id = $admin->id;
            
                        $notification->save();

                        Mail::to($admin->email)->send(new LeaveMail($admin, $setting, $leave));
                    }
                } else {
                    $mailPerm = Permission::where('name_en', 'leave_mail')->first();

                    $notifyPerm = Permission::where('name_en', 'leave_notification')->first();
                    
                    if ($notifyPerm) {
                        $rolePermission = RolePermission::select('role_id')->where('permission_id', $notifyPerm->id)->groupBy('role_id')->get();
                    } else {
                        $rolePermission = [];
                    }

                    if ($rolePermission != [] && (Auth::user()->user_type == 3)) {
                        $admins = User::where('status', 1)->whereIn('role_id', $rolePermission)->get();
                        
                        foreach ($admins as $admin) {
                            $notification = new Notification;
                    
                            $notification->type             = 1;
                            $notification->title            = "New Leave Application";
                            $notification->message          = "New Leave Application submitted by ".($leave->employee->name_en ?? '');
                            $notification->route_name       = route('admin.leave.show', Crypt::encryptString($leave->id));
                            $notification->sender_role_id   = $leave->employee->role_id ?? '';
                            $notification->sender_user_id   = $leave->user_id;
                            $notification->receiver_role_id = $admin->role_id;
                            $notification->receiver_user_id = $admin->id;
                
                            $notification->save();
                        }
                    }
                    
                    if ($mailPerm) {
                        $rolePerm = RolePermission::select('role_id')->where('permission_id', $mailPerm->id)->groupBy('role_id')->get();
                    } else {
                        $rolePerm = [];
                    }

                    if ($rolePerm != [] && (Auth::user()->user_type == 3)) {
                        $admins = User::where('status', 1)->whereIn('role_id', $rolePerm)->get();
                        
                        foreach ($admins as $admin) {
                            Mail::to($admin->email)->send(new LeaveMail($admin, $setting, $leave));
                        }
                    }
                }

                return redirect()->route('admin.leave.index')->with('success', "Leave application submitted successfully!");
            } else {
                return back()->with('error', "Leave application is found in the given date range!");
            }
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

        if (Gate::allows('show_leave', $user)) {
            $menu_expand = route('admin.leave.index');

            $id = Crypt::decryptString($id);
            $leave = Leave::where('id', $id)->first();
            $categorys = LeaveCategory::where('status', 1)->get();

            return view('backend.admin.leave.show', compact('menu_expand', 'leave', 'categorys'));
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

        if (Gate::allows('edit_leave', $user)) {
            $menu_expand = route('admin.leave.index');

            $id = Crypt::decryptString($id);
            $leave = Leave::where('id', $id)->first();
            $categorys = LeaveCategory::where('status', 1)->get();
            $employees = User::where('user_type', 3)->where('status', 1)->get();

            return view('backend.admin.leave.edit', compact('menu_expand', 'leave', 'categorys', 'employees'));
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
        
        if (Gate::allows('edit_leave', $user)) {
            $this->validate($request, [
                'user_id'           => 'required',
                'leave_category_id' => 'required',
                'from_date'         => 'required',
                'to_date'           => 'required',
                'day_count'         => 'required',
                'reason'            => 'required',
            ]);

            $id = Crypt::decryptString($id);
            $leave = Leave::where('id', $id)->first();

            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $checkSame = Leave::where('user_id', $request->user_id)
                                ->where(function($query) use ($from_date, $to_date){
                                    $query->where('from_date', $from_date)
                                            ->orWhere('to_date', $to_date);
                                })->first();

            if (!$checkSame) {
                $leave->user_id             = $request->user_id;
                $leave->leave_category_id   = $request->leave_category_id;
                $leave->from_date           = $request->from_date;
                $leave->to_date             = $request->to_date;
                $leave->day_count           = $request->day_count;
                $leave->reason              = $request->reason;
                $leave->hod_comment         = $request->hod_comment;
                $leave->updated_by          = Auth::id();
    
                $setting = Setting::first();
    
                if ($request->leave_document) {
                    $f = 'transactionDocument/' . $leave->leave_document;
                        
                    if ($leave->leave_document) {
                        if(Storage::disk('public')->exists($f))
                        {
                            Storage::disk('public')->delete($f);
                        }
                    }
    
                    $cp = $request->file('leave_document');
                    $extension = strtolower($cp->getClientOriginalExtension());
                    $randomFileName = 'leave_document'.date('Y_m_d_his').'_'.rand(10000000,99999999).'.'.$extension;
                    Storage::disk('public')->put('leaveDocument/' . $randomFileName, File::get($cp));
    
                    $leave->leave_document = $randomFileName;
                }
    
                $leave->save();

                $mailPerm = Permission::where('name_en', 'leave_mail')->first();
    
                if ((($leave->employee->user_type ?? '') == 3) && ($request->leave_category_id || $request->from_date || $request->to_date || $request->day_count || $request->reason)) {
                    $leave->status          = 0;

                    $leave->save();
                    
                    if (($leave->employee->userInfo->designation_id ?? '') != 12) {
                        $admin = User::whereHas('userInfo', function($query2) {
                                            $query2->where('designation_id', 12);
                                        })->where('id', ($leave->employee->team_id ?? ''))->where('status', 1)->first();
    
                        if ($admin && (Auth::user()->user_type == 3)) {
                            $notification = new Notification;
                    
                            $notification->type             = 1;
                            $notification->title            = "New Leave Application";
                            $notification->message          = "New Leave Application submitted by ".($leave->employee->name_en ?? '');
                            $notification->route_name       = route('admin.leave.show', Crypt::encryptString($leave->id));
                            $notification->sender_role_id   = $leave->employee->role_id ?? '';
                            $notification->sender_user_id   = $leave->user_id;
                            $notification->receiver_role_id = $admin->role_id;
                            $notification->receiver_user_id = $admin->id;
                
                            $notification->save();
    
                            Mail::to($admin->email)->send(new LeaveMail($admin, $setting, $leave));
                        }
                    } else {
                        $mailPerm = Permission::where('name_en', 'leave_mail')->first();
    
                        $notifyPerm = Permission::where('name_en', 'leave_notification')->first();
                        
                        if ($notifyPerm) {
                            $rolePermission = RolePermission::select('role_id')->where('permission_id', $notifyPerm->id)->groupBy('role_id')->get();
                        } else {
                            $rolePermission = [];
                        }
    
                        if ($rolePermission != [] && (Auth::user()->user_type == 3)) {
                            $admins = User::where('status', 1)->whereIn('role_id', $rolePermission)->get();
                            
                            foreach ($admins as $admin) {
                                $notification = new Notification;
                        
                                $notification->type             = 1;
                                $notification->title            = "New Leave Application";
                                $notification->message          = "New Leave Application submitted by ".($leave->employee->name_en ?? '');
                                $notification->route_name       = route('admin.leave.show', Crypt::encryptString($leave->id));
                                $notification->sender_role_id   = $leave->employee->role_id ?? '';
                                $notification->sender_user_id   = $leave->user_id;
                                $notification->receiver_role_id = $admin->role_id;
                                $notification->receiver_user_id = $admin->id;
                    
                                $notification->save();
                            }
                        }
                        
                        if ($mailPerm) {
                            $rolePerm = RolePermission::select('role_id')->where('permission_id', $mailPerm->id)->groupBy('role_id')->get();
                        } else {
                            $rolePerm = [];
                        }
    
                        if ($rolePerm != [] && (Auth::user()->user_type == 3)) {
                            $admins = User::where('status', 1)->whereIn('role_id', $rolePerm)->get();
                            
                            foreach ($admins as $admin) {
                                Mail::to($admin->email)->send(new LeaveMail($admin, $setting, $leave));
                            }
                        }
                    }
                }

                return redirect()->route('admin.leave.index')->with('success', "Leave application updated successfully!");
            } else {
                return back()->with('error', "Leave application is found in the given date range!");
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

        if (Gate::allows('delete_leave', $user)) {
            $id = Crypt::decryptString($id);
            
            Leave::where('id', $id)->delete();

            return redirect()->route('admin.leave.index')->with('success', 'Leave application deleted successfully!');
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    public function statusChange(Request $request, $id)
    {
        $user = Auth::user();

        if (Gate::allows('leave_status_change', $user)) {
            $id = Crypt::decryptString($id);
            $setting = Setting::first();
            $leave = Leave::where('id', $id)->first();
            $leaveUser = User::where('id', $leave->user_id)->first();

            $hodPerm = Permission::where('name_en', 'hod_permission')->first();

            if ($hodPerm) {
                $rolePerm = RolePermission::select('role_id')->where('permission_id', $hodPerm->id)->groupBy('role_id')->get();
            } else {
                $rolePerm = [];
            }

            if ($request->status == 1) {
                $leave->hod_comment     = $request->hod_comment;
                $leave->status          = 1;
                $leave->approved_by     = Auth::id();
                $leave->approved_date   = date('Y-m-d');

                $leave->save();

                $notification = new Notification;
        
                $notification->type             = 2;
                $notification->title            = "Leave Application Approved";
                $notification->message          = "Your Application for ".$leave->day_count. " days of ".$leave->category->name_en. " has been approved";
                $notification->route_name       = route('admin.leave.show', Crypt::encryptString($leave->id));
                $notification->sender_role_id   = $user->role_id ?? '';
                $notification->sender_user_id   = $user->id;
                $notification->receiver_role_id = $leave->employee->role_id ?? '';
                $notification->receiver_user_id = $leave->user_id;
    
                $notification->save();

                Mail::to($leaveUser->email)->send(new LeaveStatusMail($setting, $leave, $leaveUser));

                return redirect()->route('admin.leave.index')->with('success', 'Leave application approved successfully!');
            } else if ($request->status == 2) {
                $leave->hod_comment     = $request->hod_comment;
                $leave->status          = 2;
                $leave->rejected_by     = Auth::id();
                $leave->rejected_date   = date('Y-m-d');

                $leave->save();

                $notification = new Notification;
        
                $notification->type             = 3;
                $notification->title            = "Leave Application Declined";
                $notification->message          = "Your Application for ".$leave->day_count. " days of ".$leave->category->name_en. " has been declined";
                $notification->route_name       = route('admin.leave.show', Crypt::encryptString($leave->id));
                $notification->sender_role_id   = $user->role_id ?? '';
                $notification->sender_user_id   = $user->id;
                $notification->receiver_role_id = $leave->employee->role_id ?? '';
                $notification->receiver_user_id = $leave->user_id;
    
                $notification->save();

                Mail::to($leaveUser->email)->send(new LeaveStatusMail($setting, $leave, $leaveUser));

                return redirect()->route('admin.leave.index')->with('success', 'Leave application declined!');
            }

            if ($request->status == 3) {
                $leave->comment         = $request->comment;
                $leave->status          = 3;
                $leave->approved_by     = Auth::id();
                $leave->approved_date   = date('Y-m-d');

                $leave->save();

                if ($rolePerm != [] && (Auth::user()->user_type == 3)) {
                    $admins = User::where('status', 1)->whereIn('role_id', $rolePerm)->get();
                    
                    foreach ($admins as $admin) {
                        $leaveUser = $admin;

                        $notification = new Notification;
        
                        $notification->type             = 100;
                        $notification->title            = "Leave Application Forwarded for Approval";
                        $notification->message          = "Leave Application Forwarded for Approval";
                        $notification->route_name       = route('admin.leave.show', Crypt::encryptString($leave->id));
                        $notification->sender_role_id   = $user->role_id ?? '';
                        $notification->sender_user_id   = $user->id;
                        $notification->receiver_role_id = $leaveUser->role_id ?? '';
                        $notification->receiver_user_id = $leaveUser->id;
            
                        $notification->save();

                        Mail::to($leaveUser->email)->send(new LeaveStatusMail($setting, $leave, $leaveUser));

                        return redirect()->route('admin.leave.index')->with('success', 'Leave application forwarded successfully!');
                    }
                } else {
                    return abort(403, "You don't have permission!");
                }
            }
        } else {
            return abort(403, "You don't have permission!");
        }
    }
}
