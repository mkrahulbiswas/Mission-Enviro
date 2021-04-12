<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guidelines extends Model
{
    protected $table = 'guidelines';
    protected $fillable = array('text');
}
