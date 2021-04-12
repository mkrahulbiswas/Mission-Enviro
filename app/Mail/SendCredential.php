<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCredential extends Mailable {

    use Queueable, SerializesModels;

    public $data;

    public function __construct($data) {
        
        $this->data = $data;
    }

    
    public function build() {
        $datas = array(
            'name'=>$this->data['name'],
            'email'=>$this->data['email'],
            'password'=>$this->data['password']
        );

        return $this->from(config('constants.companyEmail'))
        ->subject('Admin Login Credentials')
        ->view('admin.mail.send_credential')
        ->with($datas);
    }
}