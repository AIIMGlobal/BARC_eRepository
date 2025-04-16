<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;

class AuthGates
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $roles = Role::count();

        for($i=1 ; $i<=$roles ; $i++)
        {
            if(auth()->user() == null)
            {
                return redirect()->route('login');

                break;
            }
            
            elseif(auth()->user()->role_id == $i)
            {
                if ($roleId = Auth::user()->role_id) {
                    Gate::before(function (User $user, $i) {
                            if ($user->role_id === $i) {
                                return true;
                            }
                        }
                    );

                    $role = Role::find($roleId);
                    
                    foreach ($role->permissions as $permission) {
                        $perm = Permission::where('id',$permission->permission_id)->first();
                        
                        if(isset($perm->name_en) and $perm->name_en!='') {
                            Gate::define($perm->name_en, function ($role) use ($perm) {
                                $rolePerms = RolePermission::where('role_id', $role->role_id)->get();
                                
                                foreach($rolePerms as  $key => $rolePerm )
                                {
                                    $a = array($perm->id);
                                    
                                    if (in_array($rolePerm->permission_id, $a)) {
                                        return true;
                                    }
                                }
                                                        
                            });
                        }
                    }
                    
                }

                return $next($request);
            }
            else
            {
                $i+1;
            }
        }
        
        
        // return $request->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')->header('Pragma','no-cache');

    }   
    
}
