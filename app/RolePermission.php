<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table='role_permission';
    protected $fillable=array( 'id', 'role_id', 'module_id', 'sub_module_id', 'module_access', 'sub_module_access', 'access_item', 'add_item', 'edit_item', 'details_item', 'delete_item', 'status_item', 'created_at', 'updated_at');
}
