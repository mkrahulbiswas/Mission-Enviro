<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnviroVocabulary extends Model
{
    protected $table = 'infographics_enviro_vocabulary';
    protected $fillable = array(
        'image',
        'title',
        'description',
        'status'
    );
}
