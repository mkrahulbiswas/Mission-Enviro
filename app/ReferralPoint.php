<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralPoint extends Model
{
    protected $table = 'referral_point';
    protected $fillable = array(
        'usedFrom',
        'usedBy',
        'status',
    );
}
