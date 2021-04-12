<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreeDownloads extends Model
{
    protected $table = 'free_downlods';
    protected $fillable = array(
        'file',
        'title',
        'description',
        'status'
    );
}
