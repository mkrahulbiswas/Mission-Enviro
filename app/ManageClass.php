<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManageClass extends Model
{
    protected $table = 'manage_class';
    protected $fillable = array(
        'levelId',
        'class',
        'status'
    );
}
