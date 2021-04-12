<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';
    protected $fillable = array(
        'adminId',
        'image',
        'testId',
        'packageId',
        'name',
        'bannerFor',
        'page',
        'status'
    );
}
