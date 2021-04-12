<?php

namespace App\Exceptions;

//use Exception;
use Throwable;  
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Mail;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
            //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
//    public function report(Exception $exception)
    public function report(Throwable $exception){
        
        if(env('APP_ENV') == 'production'){
            if ($this->shouldReport($exception)) {
                $this->sendExceptionEmail($exception);
            }
        }

        parent::report($exception);
    }

    public function sendExceptionEmail(Throwable $exception) {
        try {
            $e = FlattenException::create($exception);

            $handler = new SymfonyExceptionHandler();

            $html = $handler->getHtml($e);
            
            $email_SS = \App\global_vars::find(2);
            $tag_SS = \App\global_vars::find(3);

            $data = array('error' => $html);
            Mail::send('emails.error', $data, function($message) use ($email_SS, $tag_SS) {
                $message->to('coberto@magnusmas.com')->subject('Error');
                $message->from($email_SS, $tag_SS);
            });
        } catch (Exception $e) {
            //
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
//    public function render($request, Exception $exception)
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }

}
