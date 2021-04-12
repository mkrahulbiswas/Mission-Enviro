<?php

namespace App\Http\Middleware;

use App\RolePermission;
use App\SubModule;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $role_id = Auth::guard('admin')->user()->role_id;
        // //Check if sub admin is blocked by super admin
        // $adminStatus = Auth::guard('admin')->user()->status;
        // if ($adminStatus == 0) {
        //     Auth::guard('admin')->logout();
        //     return redirect('/admin')->with('loginErr', 'You are blocked by Super Admin');
        // }
        // //End check
        // $url = url()->current();
        // $url = explode("/", $url);
        // $subModule = SubModule::all();
        // foreach ($subModule as $temp) {
        //     $subModule = SubModule::where('last_segment', $temp->last_segment)->first();
        //     if (empty($subModule)) {
        //         //return redirect()->route('404');
        //         abort(404);
        //     }
        //     $checkPermission = RolePermission::where('role_id', $role_id)->where('sub_module_id', $subModule->id)->first();
        //     if (empty($checkPermission)) {
        //         //return redirect()->route('404');
        //         abort(404);
        //     }
        //     if (in_array($temp->last_segment, $url)) {
        //         if (!$checkPermission->sub_module_access == 1) {
        //             //return redirect()->route('404');
        //             abort(404);
        //         }
        //     }
        //     if (in_array($temp->last_segment, $url) && in_array('add', $url)) {
        //         if (!$checkPermission->add_item == 1) {
        //             //return redirect()->route('404');
        //             abort(404);
        //         }
        //     }
        //     if (in_array($temp->last_segment, $url) && in_array('edit', $url)) {
        //         if (!$checkPermission->edit_item == 1) {
        //             //return redirect()->route('404');
        //             abort(404);
        //         }
        //     }
        //     if (in_array($temp->last_segment, $url) && in_array('delete', $url)) {
        //         if (!$checkPermission->delete_item == 1) {
        //             //return redirect()->route('404');
        //             abort(404);
        //         }
        //     }
        //     if (in_array($temp->last_segment, $url) && in_array('details', $url)) {
        //         if (!$checkPermission->details_item == 1) {
        //             //return redirect()->route('404');
        //             abort(404);
        //         }
        //     }
        //     if (in_array($temp->last_segment, $url) && in_array('status', $url)) {
        //         if (!$checkPermission->status_item == 1) {
        //             //return redirect()->route('404');
        //             abort(404);
        //         }
        //     }
        // }
        return $next($request);
    }
}
