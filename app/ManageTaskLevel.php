<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManageTaskLevel extends Model
{
    use SoftDeletes;

    protected $table = 'manage_task_level';
    protected $fillable = array(
        'title',
        'dateFrom',
        'dateTo',
        'point',
        'description',
        'status'
    );
}
