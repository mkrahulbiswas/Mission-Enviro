<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManageTasks extends Model
{
    use SoftDeletes;

    protected $table = 'manage_tasks';
    protected $fillable = array(
        'taskLevelId',
        'taskQuarterId',
        'levelId',
        'date',
        'point',
        'title',
        'image',
        'description',
        'status'
    );
}
