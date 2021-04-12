<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopRankedUser extends Model
{
    protected $table = 'top_ranked_user';
    protected $fillable = array(
        'userId',
        'levelId',
        'taskLevelId',
        'taskQuarterId',
        'rankPoint',
        'pointGain',
        'status'
    );
}
