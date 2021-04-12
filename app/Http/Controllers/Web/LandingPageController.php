<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LandingPageController extends Controller
{

    /*------------ ( Landing Page ) -------------*/
    public function showLandingPage()
    {
        try {
            return view('admin.notification.send_notification.index');
        } catch (Exception $e) {
            abort(500);
        }
    }
}
