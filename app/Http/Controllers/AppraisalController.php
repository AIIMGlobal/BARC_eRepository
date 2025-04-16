<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appraisal;
use App\Models\User;
use App\Models\Post;
use App\Models\Office;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AppraisalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('user_appraisal', $user)){
        	$appraisal_query = Appraisal::whereIn('initial',[0,2])->whereIn('status',[0,1])->latest();

            if ($request->name and $request->name != '') {
                $appraisal_query->whereHas('employeeInfo', function($query) use ($request) {
                    $query->where(function($query1) use($request) {
                        $query1->where('name_en', 'like','%'.$request->name.'%')->orWhere('name_bn', 'like','%'.$request->name.'%');
                    });
                });
            }

            if(!Gate::allows('access_all_appraisal_list', $user)){
                if($user->user_type == 3){
                    $office_info = $user->userInfo->office ?? '';
                    if (($office_info->head_office ?? 0) == 1) {
                        if(Gate::allows('access_main_office_all_appraisal', $user)){
                            $office_ids = $office_info->officeIds($office_info->division_id);
                            if (count($office_ids) == 0) {
                                $office_ids = [0];
                            }
                            $appraisal_query->whereIn('office_id', $office_ids);
                        } else {
                            $appraisal_query->where('user_id', $user->id);
                        }
                        
                    } else {
                        $appraisal_query->where('user_id', $user->id);
                    }
                    
                }
            }
            
            $appraisals = $appraisal_query->whereRaw('id IN (select MAX(id) FROM appraisals where status in (0,1) GROUP BY user_id)')->paginate(20);
            return view('backend.admin.appraisal.index', compact('appraisals'));
        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if(Gate::allows('add_appraisal', $user)){
            $menu_expand = route('admin.appraisal.index');
            $users = User::where('user_type', 3)->where('status', 1)->get();
            $posts = Post::where('status', 1)->get();
            $offices = Office::where('status', 1)->get();
            return view('backend.admin.appraisal.create', compact('menu_expand','users','posts','offices'));
        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();
        if(Gate::allows('add_appraisal', $user)){

            DB::transaction(function () use ($request,$user) {
                $get_user = User::find($request->user_id);

                $appraisal['user_id'] = $request->user_id;
                $appraisal['office_id'] = $request->transfer_applied ? $request->transfer_id : ($get_user->userInfo->office_id ?? 0);
                $appraisal['status'] = $request->status ?? 0;
                $appraisal['start'] = $request->start;
                $appraisal['end'] = $request->end;
                $appraisal['transfer_applied'] = $request->transfer_applied ?? 0;
                $appraisal['comments'] = $request->comments;
                $appraisal['created_by'] = auth()->user()->id;
                $appraisal['post_id'] = $request->post_id;
                $appraisal['basic_salary'] = $request->basic_salary;
                if(($request->start <= Carbon::now())){
                    $appraisal['initial'] = ($request->status ?? 0) == 1 ? 2:0;
                } else {
                    $appraisal['initial'] = 0;
                }
    
                if($request->transfer_applied == 1) {
                    $transfer['user_id'] = $request->user_id;
                    $transfer['office_id'] = $request->transfer_applied ? $request->transfer_id : ($get_user->userInfo->office_id ?? 0);
                    $transfer['post_id'] = $request->post_id;
                    $transfer['status'] = $request->status ?? 0;
                    $transfer['date_from'] = $request->start;
                    $transfer['date_to'] = $request->end;
                    if(($request->start <= Carbon::now())){
                        $transfer['initial'] = ($request->status ?? 0) == 1 ? 2:0;
                    } else {
                        $transfer['initial'] = 0;
                    }
                    if ($transfer['initial'] == 2) {
                        Transfer::where([
                            'user_id' => $request->user_id,
                            'initial' => 2,
                        ])->update([
                            'initial' => 0,
                        ]);
                    }

                    $previous_transfer = Transfer::where('user_id',$request->user_id)->where('status',1)->latest()->first();

                    if($previous_transfer){
                        $previous_transfer->update([
                            'date_to' => date('Y-m-d', strtotime($request->start." -1 day")),
                        ]);
                    }

                    

                    $transfer['created_by'] = auth()->user()->id;
                    $transfer_data = Transfer::create($transfer);
                    $appraisal['transfer_id']  = $transfer_data->id;
                }

                if ($appraisal['initial'] == 2) {
                    $previous_appraisal = Appraisal::where('user_id',$request->user_id)->where('status',1)->latest()->first();

                    if($previous_appraisal){
                        $previous_appraisal->update([
                            'end' => date('Y-m-d', strtotime($request->start." -1 day")),
                        ]);
                    }

                    if (($previous_appraisal->initial ?? 0) == 2 and $appraisal['initial'] == 2) {
                        $previous_appraisal->update([
                            'initial' => 0,
                        ]);
                    }
                }

                if($request->have_document == 1) {
                    $document_array = [];
    
                    foreach ($request->file as $key => $file) {
                        if ($request->file('file')[$key]) {
                            
                            $path = $request->file('file')[$key]->store('/public/appraisal');
                            
                            $path = Str::replace('public/appraisal', '', $path);
                            $appraisalimage = Str::replace('/', '', $path);
    
                            $data = array(
                                "index" => $key + 1,
                                "title" => $request->file_title[$key],
                                "file" => $appraisalimage,
                            );
    
    
                            array_push($document_array,$data);
                        }
                    }
    
                    $appraisal['documents'] = json_encode($document_array);
                }
    
                Appraisal::create($appraisal);

                if($appraisal['initial'] == 2){ 
                    $get_user->userInfo->update([
                        'post_id' => $request->post_id,
                        'basic_salary' => $request->basic_salary,
                    ]);
                }
            });

            return redirect()->route('admin.appraisal.index')->with('success', 'New appraisal added successfully..!');

        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $user = Auth::user();
        $id = Crypt::decryptString($id);
        if(Gate::allows('edit_appraisal', $user)){
            
            DB::transaction(function () use ($request,$user,$id) {
                $appraisal_data = Appraisal::where('id', $id)->first();
                $get_user = User::find($request->user_id);

                $appraisal['user_id'] = $request->user_id;
                $appraisal['office_id'] = $request->transfer_applied ? $request->transfer_id : $appraisal_data->office_id;
                $appraisal['status'] = $request->status ?? 0;
                $appraisal['start'] = $request->start;
                $appraisal['end'] = $request->end;
                $appraisal['transfer_applied'] = $request->transfer_applied ?? 0;
                $appraisal['comments'] = $request->comments;
                $appraisal['updated_by'] = auth()->user()->id;
                $appraisal['post_id'] = $request->post_id;
                $appraisal['basic_salary'] = $request->basic_salary;

                if(($request->start <= Carbon::now())){
                    $appraisal['initial'] = ($request->status ?? 0) == 1 ? 2:0;
                } else {
                    $appraisal['initial'] = 0;
                }
    
                if($request->transfer_applied == 1) {
                    $transfer['user_id'] = $request->user_id;
                    $transfer['office_id'] = $request->transfer_applied ? $request->transfer_id : ($get_user->userInfo->office_id ?? 0);
                    $transfer['post_id'] = $request->post_id;
                    $transfer['status'] = $request->status ?? 0;
                    $transfer['date_from'] = $request->start;
                    $transfer['date_to'] = $request->end;
                    $transfer['updated_by'] = auth()->user()->id;
                    if(($request->start <= Carbon::now())){
                        $transfer['initial'] = ($request->status ?? 0) == 1 ? 2:0;
                    } else {
                        $transfer['initial'] = 0;
                    }

                    if ($transfer['initial'] == 2) {
                        Transfer::where('user_id',$request->user_id)->update([
                            'initial' => 0,
                        ]);
                    }

                    $previous_transfer_query = Transfer::where('user_id',$request->user_id);

                    if ($appraisal_data->transfer_id) {
                        $previous_transfer_query->where('id', '<' , $appraisal_data->transfer_id);
                    }
                    
                    
                    $previous_transfer = $previous_transfer_query->latest()->first();

                    if($previous_transfer){
                        $previous_transfer->update([
                            'date_to' => date('Y-m-d', strtotime($request->start." -1 day")),
                        ]);
                    }

                    if ($appraisal_data->transfer_id) {
                        $transfer_data = Transfer::where('id',$appraisal_data->transfer_id)->first();
                        if ($transfer_data) {
                            $transfer_data->update($transfer);
                        } else {
                            $transfer_data = Transfer::create($transfer);
                        }
                    } else {
                        $transfer_data = Transfer::create($transfer);
                    }
                    
                    $appraisal['transfer_id']  = $transfer_data->id;
                } else {
                    if ($appraisal_data->transfer_id) {
                        $transfer_data = Transfer::where('id',$appraisal_data->transfer_id)->first();
                        if ($transfer_data) {
                            $transfer_data->update([
                                'status' => 2
                            ]);
                        }
                    }
                }
    
                if($request->have_document == 1) {
                    $document_array = [];
    
                    foreach ($request->file_title as $key => $file) {
                        if ($request->delete[$key] == 0) {
                            if (($request->file('file')[$key] ?? '')) {
                                
                                $path = $request->file('file')[$key]->store('/public/appraisal');
                                
                                $path = Str::replace('public/appraisal', '', $path);
                                $appraisalimage = Str::replace('/', '', $path);
        
                                $data = array(
                                    "index" => $key + 1,
                                    "title" => $request->file_title[$key],
                                    "file" => $appraisalimage,
                                );
        
        
                                array_push($document_array,$data);
                            } else {
                                $data = array(
                                    "index" => $key + 1,
                                    "title" => $request->file_title[$key],
                                    "file" => $request->file_name[$key] ? $request->file_name[$key] : '',
                                );
        
        
                                array_push($document_array,$data);

                            }
                        }
                    }
    
                    $appraisal['documents'] = json_encode($document_array);
                } else {
                    $appraisal['documents'] = NULL;
                }
    
                $appraisal_data->update($appraisal);

                $previous_appraisal = Appraisal::where('user_id',$request->user_id)->where('id', '<', $id)->latest()->first();

                if($previous_appraisal){
                    $previous_appraisal->update([
                        'end' => date('Y-m-d', strtotime($request->start." -1 day")),
                    ]);
                }

                if(($request->start <= Carbon::now()) and $request->status == 1){
                    $get_user->userInfo->update([
                        'post_id' => $request->post_id,
                        'basic_salary' => $request->basic_salary,
                    ]);
                }
            });
            return redirect()->route('admin.appraisal.index')->with('success', 'Appraisal updated successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Crypt::decryptString($id);
        $user = Auth::user();
        if(Gate::allows('delete_appraisal', $user)){
            $appraisal = Appraisal::where('id', $id)->first();
            // dd($appraisal);
            if ($appraisal->initial == 2) {
                $previous_appraisals = Appraisal::where([
                    'user_id' => $appraisal->user_id,
                    'status' => 1,
                    // 'initial' => 0,
                ])->where('id','<',$id)->first();
// dd($previous_appraisals);
                if ($previous_appraisals) {
                    if ($previous_appraisals->initial != 1) {
                        $previous_appraisals->update(['initial' => 2]);
                    }
                    $previous_appraisals->userInfoData->update(
                        [
                            'office_id' => $previous_appraisals->office_id,
                            'post_id' => $previous_appraisals->post_id,
                            'basic_salary' => $previous_appraisals->basic_salary,
                        ]
                    );

                }
            }
            if ($appraisal->transfer_applied == 1) {
                

                if (($appraisal->transferInfo->initial ?? 0) == 2) {
                    if ($appraisal->transfer_id > 0) {
                        $appraisal->transferInfo->update(['status'=>2]);
                    }
                }
            }
            $appraisal->status = 2;
            $appraisal->save();
            return redirect()->route('admin.appraisal.index')->with('success', 'Appraisal deleted successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    public function edit($id)
    {
        $id = Crypt::decryptString($id);
        $user = Auth::user();
        if(Gate::allows('edit_appraisal', $user)){
            $appraisal = Appraisal::where('id', $id)->first();
            $menu_expand = route('admin.appraisal.index');
            $users = User::where('user_type', 3)->where('status', 1)->get();
            $posts = Post::where('status', 1)->get();
            $offices = Office::where('status', 1)->get();
            return view('backend.admin.appraisal.edit', compact('menu_expand','users','posts','offices','appraisal'));
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function show($id)
    {
        $id = Crypt::decryptString($id);
        $user = Auth::user();
        if(Gate::allows('view_appraisal', $user)){
            $appraisal = Appraisal::where('id', $id)->first();
            $menu_expand = route('admin.appraisal.index');
            $users = User::where('user_type', 3)->where('status', 1)->get();
            $posts = Post::where('status', 1)->get();
            $offices = Office::where('status', 1)->get();
            return view('backend.admin.appraisal.view', compact('menu_expand','users','posts','offices','appraisal'));
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function view_history($id)
    {
        $id = Crypt::decryptString($id);
        $user = Auth::user();
        if(Gate::allows('view_appraisal', $user)){
            $menu_expand = route('admin.appraisal.index');
            $appraisal_query = Appraisal::where('initial','!=',1)->latest();
            $appraisals = $appraisal_query->where('user_id',$id)->with('employeeInfo')->whereIn('status',[0,1])->paginate(20);
            return view('backend.admin.appraisal.history', compact('appraisals','menu_expand'));
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
}
