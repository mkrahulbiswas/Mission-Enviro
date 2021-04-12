<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubModule extends Model
{
    protected $table = 'sub_module';
    protected $fillable = array('id', 'module_id', 'name', 'link', 'last_segment', 'add_action', 'edit_action', 'details_action', 'delete_action', 'status_action', 'created_at', 'updated_at');
}
