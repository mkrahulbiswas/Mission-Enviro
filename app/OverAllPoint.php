<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OverAllPoint extends Model
{
    protected $table = 'over_all_point';
    protected $fillable = array(
        'taskLevelId',
        'taskQuarterId',
        'userId',
        'champLevelId',
        'poit',
        'status'
    );
}
