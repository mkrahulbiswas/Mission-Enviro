<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManageTaskQuarter extends Model
{
    use SoftDeletes;

    protected $table = 'manage_task_quarter';
    protected $fillable = array(
        'title',
        'dateFrom',
        'dateTo',
        'point',
        'description',
        'taskLevelId',
        'rankPoint',
        'status'
    );
}
