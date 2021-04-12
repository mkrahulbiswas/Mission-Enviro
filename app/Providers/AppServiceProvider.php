<?php

namespace App\Providers;

use App\Contact;
use App\SubModule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);

        view()->composer('*', function ($view) {
            $url = url()->current();
            $url = explode("/", $url);
            if (in_array('admin', $url)) {
                if (Auth::guard('admin')->check()) {
                    $role_id = Auth::guard('admin')->user()->role_id;

                    $modules = DB::table('module')
                        ->join('role_permission', 'role_permission.module_id', '=', 'module.id')
                        ->distinct()
                        ->select('module.*')
                        ->where('role_permission.role_id', $role_id)
                        ->where('role_permission.module_access', 1)
                        ->orderBy('module.orders', 'asc')
                        ->get();

                    //print_r($modules); exit();

                    $subModules = DB::table('sub_module')
                        ->join('role_permission', 'role_permission.sub_module_id', '=', 'sub_module.id')
                        ->select('sub_module.*')
                        ->where('role_permission.role_id', $role_id)
                        ->where('role_permission.sub_module_access', 1)
                        ->get();

                    $subModuleGroup = SubModule::groupBy('module_id')->select(DB::raw('count(name) as count,module_id'))->get();

                    $adminDetails = DB::table('admins')
                        ->join('role', 'role.id', '=', 'admins.role_id')
                        ->select('admins.*', 'role.role')
                        ->where('admins.id', Auth::guard('admin')->user()->id)
                        ->first();

                    //echo $role_id; exit();
                    $itemPermission = DB::table('role_permission')
                        ->join('sub_module', 'sub_module.id', '=', 'role_permission.sub_module_id')
                        ->select('role_permission.*', 'sub_module.last_segment')
                        ->where('role_permission.role_id', $role_id)
                        ->where('role_permission.sub_module_access', '1')
                        ->get();

                    //print_r($itemPermission);exit();
                    foreach ($itemPermission as $temp) {
                        if (in_array($temp->last_segment, $url)) {
                            $permission = array("add_item" => $temp->add_item, "edit_item" => $temp->edit_item, "details_item" => $temp->details_item, "delete_item" => $temp->delete_item, "status_item" => $temp->status_item);
                            goto a;
                        } else {
                            $permission = array("add_item" => '0', "edit_item" => '0', "details_item" => '0', "delete_item" => 0, "status_item" => 0);
                        }
                    }

                    a: 
                    $permission = array("add_item" => '1', "edit_item" => '1', "details_item" => '1', "delete_item" => '1', "status_item" => '1');

                    View::share('modules', $modules);
                    View::share('subModules', $subModules);
                    View::share('subModuleGroup', $subModuleGroup);
                    View::share('adminDetails', $adminDetails);
                    View::share('itemPermission', $permission);
                }

                $appName = str_replace('_', ' ', config('app.name'));
                View::share('appName', $appName);
            } else {
                $contact = Contact::first();
                View::share('contact', $contact);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
