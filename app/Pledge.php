<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pledge extends Model
{
    protected $table = 'pledge';
    protected $fillable = array(
        'file',
        'status'
    );
}
