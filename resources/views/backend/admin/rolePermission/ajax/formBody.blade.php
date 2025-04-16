<h5 class="text-center"> {{__('pages.Given Permissions')}}</h5>
<button class="btn btn-sm btn-success mb-2" id="selectAllAssigned">{{__('pages.Select All')}}</button>
<button class="btn btn-sm btn-danger mb-2" id="removeAllAssigned">{{__('pages.Remove All')}}</button>
<form action="{{route('admin.rolePermission.removePermission')}}" method="POST" style=" border: 1px solid rgba(0, 0, 0, 0.319);">
    @csrf
    @foreach ($rolePermissions as $permission)
        <input type="hidden" name="hiddenRoleId" value="{{$permission->role_id}}">
    @endforeach
    <div id="assignedPermissionList" class="row card-body" style="overflow: hidden;">
        @php
            $i = 1;
        @endphp
        @foreach ($rolePermissions as $permission)
            <div class="bg-soft-success m-1 p-2 col-xxl-2" style="border:1px solid rgba(0, 0, 0, 0.319);">
                <input class="assignedPermissions" type="checkbox" name="removePermission[]" id="removePermission{{$i}}" value="{{$permission->permission_id}}">
                <label class="form-check-label" for="removePermission{{$i}}">
                    {{$permission->permissionName ? $permission->permissionName->name_en : "-"}}
                </label>
            </div>
            @php
                $i++;
            @endphp
        @endforeach
    </div>
    <div class="card-footer">
        @can('remove_assign_permission')
            <button type="submit" class="btn btn-danger">{{__('pages.Remove Permission')}}</button>
        @endcan
    </div>
</form>


<h5 class="text-center mt-5">{{__('pages.Non-Given Permissions')}}</h5>
<button class="btn btn-sm btn-success mb-2" id="selectAllUnassigned">{{__('pages.Select All')}}</button>
<button class="btn btn-sm btn-danger mb-2" id="removeAllUnassigned">{{__('pages.Remove All')}}</button>
<form action="{{route('admin.rolePermission.givePermission')}}" method="POST" style=" border: 1px solid rgba(0, 0, 0, 0.319);">
    @csrf
        <input type="hidden" name="hiddenRoleId" value="{{$selected_role_id}}">
    <div id="unassignedPermissionList" class="row card-body" style="overflow: hidden">
        @php
            $i = 1;
        @endphp
        @foreach ($unassignedPermissions as $up)
            <div class="bg-soft-danger m-1 p-2 col-xxl-2" style="border:1px solid rgba(0, 0, 0, 0.319);">
                <input class="unassignedPermissions" type="checkbox" name="givePermission[]" id="givePermission{{$i}}" value="{{$up->id}}">
                <label class="form-check-label" for="givePermission{{$i}}">
                    {{$up->name_en ?? "-"}}
                </label>
            </div>
            @php
                $i++;
            @endphp
        @endforeach
    </div>
    <div class="card-footer">
        @can('assign_permission')
            <button type="submit" class="btn btn-success">{{__('pages.Give Permisison')}}</button>
        @endcan
    </div>
</form>
