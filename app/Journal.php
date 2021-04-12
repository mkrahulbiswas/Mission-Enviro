<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $table = 'journal';
    protected $fillable = array(
        'userId',
        'title',
        'description',
        'image',
        'status'
    );
}
