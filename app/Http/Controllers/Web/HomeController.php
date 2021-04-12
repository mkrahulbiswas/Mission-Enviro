<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Banner;
use App\Traits\FileTrait;
use App\Traits\ValidationTrait;

class HomeController extends Controller
{
    use FileTrait, ValidationTrait;
    public $platform = 'web';

    public function homeShow()
    {
        return view('web.home.index');
    }
}
