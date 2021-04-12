<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskResult extends Model
{
    protected $table = 'task_result';
    protected $fillable = array(
        'userId',
        'taskId',
        'classId',
        'levelId',
        'taskLevelId',
        'taskQuarterId',
        'image',
        'description',
        'point',
        'status',
    );
}
