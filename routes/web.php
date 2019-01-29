<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

use Webklex\IMAP\Client;

Route::get('/test', function () {


    $before = microtime(true);

    for ($i = 0; $i < 100000; $i++) {
        serialize([1, 'dos' => 2, 3, 50, 22, 'index' => [1, 2, 3, 'sdf' => 'sdf', 'index2' => [1, 2, 3, 'sdf' => 'sdf']], 'sdf' => 'sdf', 'index2' => [1, 2, 3, 'sdf' => 'sdf']]);
    }

    $after = microtime(true);
    echo ($after - $before) / $i . " sec/serialize\n";
    exit;

    config([
        'mail.driver' => 'smtp',
        'mail.host' => 'smtp.1and1.com',
        'mail.port' => 587,
        'mail.encryption' => 'tls',
        'mail.username' => 'jayne@sweettravels.co',
        'mail.password' => 'Se3OT!me1'
    ]);
    $app = \App::getInstance();
    $app->singleton('swift.transport', function ($app) {
        return new \Illuminate\Mail\TransportManager($app);
    });
    $mailer = new \Swift_Mailer($app['swift.transport']->driver());
    \Mail::setSwiftMailer($mailer);


    $to = ['labrest03@gmail.com'];
    $subject = 'subject';
    $username = 'jayne@sweettravels.co';
    $text = 'texttt';


    $send = \Mail::raw($text, function($message)use($to, $subject, $username) {

                $message->subject($subject);
                $message->from($username, $username);
                $message->replyTo($username, $username);

                foreach ((array) $to as $email) {
                    $message->to($email);
                }
            });


    if (count(Mail::failures()) > 0) {
        $errors = 'Failed to send password reset email, please try again.';
    }
    var_dump(Mail::failures());

    exit;


    return view('welcome');
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', 'HomeController@index')->name('home');



    Route::get('/{vue_capture?}', function () {
        return view('dashboard');
    })->where('vue_capture', '[\/\w\.-]*');
});



