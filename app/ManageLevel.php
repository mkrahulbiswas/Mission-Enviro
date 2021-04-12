<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManageLevel extends Model
{
    protected $table = 'manage_level';
    protected $fillable = array(
        'name',
        'status'
    );
}
