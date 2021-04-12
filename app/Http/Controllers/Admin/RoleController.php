<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Admin;
use App\Role;
use App\RolePermission;
use App\SubModule;

use Validator;
use DB;


class RoleController extends Controller
{
    ///////////////Role///////////////
    public function showRole()
    {
        $role = Role::all();
        // $role=Role::where('id','!=',1)->get();
        return view('admin.role_permission.roles', ['role' => $role]);
    }

    public function saveRole(Request $request)
    {
        $values = $request->only('role', 'role_description');
        //echo $values['role']; exit();
        $validator = Validator::make(
            $request->all(),
            [
                //'email' => 'required|email|',
                'role' => 'required',
                'role_description' => 'required|max:500',
            ],
            [
                'role.required' => 'This field is required.',
                'role_description.required' => 'This field is required.',
            ]
        );

        if ($validator->fails()) {
            return redirect('/admin/roles-permissions/roles'); //->withErrors($validator);
        }

        $role = new Role;
        $role->role = $values['role'];
        $role->role_description = $values['role_description'];
        if ($role->save()) {
            $role_id = $role->id;
            $sub_module = SubModule::all();
            //print_r($sub_module); exit();

            foreach ($sub_module as $temp) {
                //echo $temp->id;
                $permission = new RolePermission;
                $permission->role_id = $role_id;
                $permission->module_id = $temp->module_id;
                $permission->sub_module_id = $temp->id;
                $permission->module_access = 0;
                $permission->sub_module_access = 0;
                $permission->access_item = 0;
                $permission->add_item = 0;
                $permission->edit_item = 0;
                $permission->details_item = 0;
                $permission->delete_item = 0;
                $permission->status_item = 0;
                $permission->save();
            }

            return redirect('/admin/roles-permissions/roles')->with('success', 'Role successfully saved.');
        } else {
            return redirect('/admin/roles-permissions/roles')->with('error', 'Something went wrong.');
        }
    }

    // public function deleteRole($id)
    // {
    //     $role = Role::find($id);
    //     if ($role->delete()) {
    //         RolePermission::where('role_id', $id)->delete();
    //         Admin::where('role_id', $id)->delete();
    //         return redirect('/admin/roles-permissions/roles')->with('success', 'Role successfully deleted.');
    //     } else {
    //         return redirect('/admin/roles-permissions/roles')->with('error', 'Something went wrong.');
    //     }
    // }

    //////////////Permissions///////////////
    public function showPermission()
    {
        $role = Role::all();
        // $role = Role::where('id', '!=', 1)->get();

        return view('admin.role_permission.permissions', ['role' => $role]);
    }

    public function showEditPermission($id)
    {
        //$user = auth()->guard('admin')->user();

        $permissions = DB::table('role_permission')
            ->join('module', 'module.id', '=', 'role_permission.module_id')
            ->join('sub_module', 'sub_module.id', '=', 'role_permission.sub_module_id')
            ->select('role_permission.*', 'module.name as module_name', 'sub_module.name as sub_module_name', 'sub_module.add_action', 'sub_module.edit_action', 'sub_module.details_action', 'sub_module.delete_action', 'sub_module.status_action')
            ->where('role_permission.role_id', $id)
            ->get();

        return view('admin.role_permission.edit_permissions', ['permissions' => $permissions]);
    }

    public function updatePermission(Request $request)
    {
        $user = auth()->guard('admin')->user();
        $ids = $request->get('id');
        $role_id = $request->get('role_id');
        //print_r($ids); exit();
        //for($i=$ids[0]; $i<=count($ids); $i++)
        foreach ($ids as $i) {
            $access_item = $request->get('access_item' . $i);
            $add_item = $request->get('add_item' . $i);
            $edit_item = $request->get('edit_item' . $i);
            $details_item = $request->get('details_item' . $i);
            $delete_item = $request->get('delete_item' . $i);
            $status_item = $request->get('status_item' . $i);

            $permission = RolePermission::find($i);
            if (!empty($access_item)) {
                $permission->access_item = $access_item;
            } else {
                $permission->access_item = 0;
            }
            if (!empty($add_item)) {
                $permission->add_item = $add_item;
            } else {
                $permission->add_item = 0;
            }
            if (!empty($edit_item)) {
                $permission->edit_item = $edit_item;
            } else {
                $permission->edit_item = 0;
            }
            if (!empty($details_item)) {
                $permission->details_item = $details_item;
            } else {
                $permission->details_item = 0;
            }
            if (!empty($delete_item)) {
                $permission->delete_item = $delete_item;
            } else {
                $permission->delete_item = 0;
            }
            if (!empty($status_item)) {
                $permission->status_item = $status_item;
            } else {
                $permission->status_item = 0;
            }


            $permission->update();

            ///check all permission like access add etc of a sub module. because if all permission is 0 then sub module access permission is 0.
            $permissions = RolePermission::find($i);
            if ($permissions->access_item == 0) {
                $permissions->sub_module_access = 0;
            } else {
                $permissions->sub_module_access = 1;
            }
            $permissions->update();
        }

        ////check all sub_module_access permission. Because if all sub_module_access permission is 0 then module_access permission is 0 
        $module_id = 0;
        foreach ($ids as $i) {
            $permissions = RolePermission::find($i);
            if ($permissions->module_id != $module_id) {
                $module_id = $permissions->module_id;
                $sub_module_permission = RolePermission::where('module_id', $module_id)->where('role_id', $role_id)->select('sub_module_access')->distinct()->get();
                //echo json_encode($sub_module_permission);exit();
                $arr = array();
                foreach ($sub_module_permission as $temp) {
                    array_push($arr, $temp->sub_module_access);
                    if (in_array(1, $arr)) {
                        DB::table('role_permission')->where('module_id', $module_id)->where('role_id', $role_id)->update(['module_access' => 1]);
                    } else {
                        DB::table('role_permission')->where('module_id', $module_id)->where('role_id', $role_id)->update(['module_access' => 0]);
                    }
                }
            }
        }


        return redirect('admin/roles-permissions/permissions/edit/' . $role_id)->with('success', 'Permission successfully updated.');
        //$permissions=RolePermission::where('role_id',$user->role);
    }
}
