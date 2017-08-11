<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\DB;
use App\Permission;
class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 15;

        if (!empty($keyword)) {
            $roles = Role::where('name', 'LIKE', "%$keyword%")->orWhere('label', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $roles = Role::paginate($perPage);
        }

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        $role = Role::create($request->all());
        $role->permissions()->detach();
        $permissions = $request->input('selected_permissions');
        foreach ($permissions as $perm_id) {
            $permission = Permission::find($perm_id);
            $role->givePermissionTo($permission);
        }
        Session::flash('flash_message', 'Role added!');

        return redirect('admin/roles');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        $permissions = DB::table('permission_role')
                    ->join('permissions','permissions.id','=','permission_role.permission_id')
                    ->select('permissions.name as permission_name','permissions.label as permission_label')
                    ->where('role_id',$role->id)->get();
        return view('admin.roles.show', compact('role','permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $selectedPermissions = DB::table('permission_role')
                    ->join('permissions','permissions.id','=','permission_role.permission_id')
                    ->select('permissions.name as permission_name','permissions.label as permission_label','permissions.id as permission_id')
                    ->where('role_id',$role->id)->get();
        $permissions = Permission::all();
        foreach($permissions as $key=>$perm){
            foreach($selectedPermissions as $p){
                if($perm->id == $p->permission_id){
                    unset($permissions[$key]);
                }
            }
        }
        return view('admin.roles.edit', compact('role','permissions','selectedPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int      $id
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        $role = Role::with('permissions')->find($id);
        $role->permissions()->detach();
        $permissions = $request->input('selected_permissions');
        foreach ($permissions as $perm_id) {
            $permission = Permission::find($perm_id);
            $role->givePermissionTo($permission);
        }
        $role = Role::findOrFail($id);
        $role->update($request->all());

        Session::flash('flash_message', 'Role updated!');

        return redirect('admin/roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        Role::destroy($id);

        Session::flash('flash_message', 'Role deleted!');

        return redirect('admin/roles');
    }
}
