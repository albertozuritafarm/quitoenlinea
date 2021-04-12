<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Hash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
Use Redirect;

class MailController extends Controller {

    //Send Welcome Mail
    function welcomeMail($customerId, $saleId) {
        $emailJob = (new SendEmailJob())->delay(Carbon::now()->addSeconds(3));
        dispatch($emailJob);
        echo 'email sent';
    }

    public function basic_email() {
        $data = array('name' => "Virat Gandhi");

        Mail::send(['text' => 'mail'], $data, function($message) {
            $message->to('coberto@magnusmas.com', 'Tutorials Point')->subject
                    ('Laravel Basic Testing Mail');
            $message->from('noreply@magnusmas.com', 'NoReply');
        });
        echo "Basic Email Sent. Check your inbox.";
    }

    public function html_email() {
        $data = array('name' => "Virat Gandhi");
        Mail::send('mail', $data, function($message) {
            $message->to('abc@gmail.com', 'Tutorials Point')->subject
                    ('Laravel HTML Testing Mail');
            $message->from('xyz@gmail.com', 'Virat Gandhi');
        });
        echo "HTML Email Sent. Check your inbox.";
    }

    public function attachment_email() {
        $data = array('name' => "Virat Gandhi");
        Mail::send('mail', $data, function($message) {
            $message->to('abc@gmail.com', 'Tutorials Point')->subject
                    ('Laravel Testing Mail with Attachment');
            $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
            $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
            $message->from('xyz@gmail.com', 'Virat Gandhi');
        });
        echo "Email Sent with attachment. Check your inbox.";
    }

    public function recover() {
        //Generate Password
        $pass = randomPassword();

        //Obtain User - Update Password
        $userEmail = DB::select('select * from users where email = ?', [request('email')]);
        $user = \App\user::find($userEmail[0]->id);
        $user->password = Hash::make($pass);
        $user->save();

        $data = array('name' => $user->first_name . ' ' . $user->last_name, 'email' => $user->email, 'pass' => $pass);        //Mail Message

        $email_SS = \App\global_vars::find(2);
        $tag_SS = \App\global_vars::find(3);
        
        Mail::send('emails.recover', $data, function($message) use($user, $email_SS, $tag_SS) {
            $message->to($user->email, $user->first_name)->subject
                    ('Ha realizado un cambio de Contraseña');
            $message->from($email_SS->value, $tag_SS->value);
        });

//       return Redirect::back()->withInput()->withErrors(['Email', 'Se envio un correo con su nueva contraseña']);
        return Redirect::back()->withInput()->with('Recover', 'Se envio un correo con su contraseña');
    }

}
