<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiHandler;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    } 

    public function render($request, Throwable $exception)
    {
        if($request->is('api/*')){
            $respostaPersonalizada = $this->tratarErros($exception);
            if($respostaPersonalizada)
            {
                return $respostaPersonalizada;
            }
        }
        return parent::render($request,$exception);
    }
}
