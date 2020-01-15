<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];
    /*protected function prepareResponse($request, Exception $e) {
        if ($this->isHttpException($e)) {
            return $this->toIlluminateResponse($this->renderHttpException($e), $e);
        } else {
            return response()->view("errors.500", ['exception' => $e]);//By overriding this function, I make Laravel display my custom 500 error page instead of the 'Whoops, looks like something went wrong.' message in Symfony\Component\Debug\ExceptionHandler
        }
    }*/
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
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        if($this->isHttpException($e)){
            if (view()->exists('errors.'.$e->getStatusCode()))
            {
                return response()->view('errors.'.$e->getStatusCode(), [], $e->getStatusCode());
            }else{
                return response()->view('errors.custom', [], $e->getStatusCode());
            }
        }

        return parent::render($request, $e);
    }
}
