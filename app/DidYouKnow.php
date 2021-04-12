<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DidYouKnow extends Model
{
    protected $table = 'infographics_did_you_know';
    protected $fillable = array(
        'image',
        'title',
        'description',
        'status'
    );
}
