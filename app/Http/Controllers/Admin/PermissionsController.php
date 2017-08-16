<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Role;
use Illuminate\Support\Facades\DB;
class PermissionsController extends Controller
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
            $permissions = Permission::where('name', 'LIKE', "%$keyword%")->orWhere('label', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $permissions = Permission::paginate($perPage);
        }

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        
        $permission = Permission::create($request->all());
        $role = Role::find(1);//get the super admin role
        $permissions = DB::table('permission_role')->where('role_id',1)->get();
        if($permissions){
            $roleHasPermission  = false;
            foreach($permissions as $perm){
                if($perm->permission_id==$permission->id){
                    $roleHasPermission = true;
                    break;
                }
            }
            if(!$roleHasPermission){
                $role->givePermissionTo($permission);
            }
        }
        Session::flash('flash_message', 'Permission added!');
        
        return redirect('admin/permissions');
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
        $permission = Permission::findOrFail($id);

        return view('admin.permissions.show', compact('permission'));
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
        $permission = Permission::findOrFail($id);

        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        $permission = Permission::findOrFail($id);
        $permission->update($request->all());
        $role = Role::find(1);//get the super admin role
        $permissions = DB::table('permission_role')->where('role_id',1)->get();
        if($permissions->count()){
            $roleHasPermission  = false;
            foreach($permissions as $perm){
                if($perm->permission_id==$permission->id){
                    $roleHasPermission = true;
                    break;
                }
            }
            if(!$roleHasPermission){
                $role->givePermissionTo($permission);
            }
        }
        Session::flash('flash_message', 'Permission updated!');

        return redirect('admin/permissions');
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
        Permission::destroy($id);

        Session::flash('flash_message', 'Permission deleted!');

        return redirect('admin/permissions');
    }
}
