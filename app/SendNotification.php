<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SendNotification extends Model
{
    use SoftDeletes;
    protected $table = 'send_notification';
    protected $fillable = array('title', 'message', 'data');
}
