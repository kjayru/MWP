<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
    public function render($request, Exception $exception)
    {
        if($exception instanceof ValidationException){
         return $this->convertValidationExceptionToResponse($exception, $request);
        }
        if($exception instanceof ModelNotFoundException){
            $modelName = strtolower(class_basename($exception->getModel()));

         return $this->errorResponse("No existe ningun modelo {$modelName} con este identificador",404);
        }
        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request, $exception);
           }
        if($exception instanceof AuthorizationException){
            return $this->errorResponse($exception->getMessage(), 403);
        }
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('El metodo especificado es invalido', 405);
        }
        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse('La url especificada no fue encontrada.', 404);
        }
        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if($exception instanceof QueryException)
        {
            $erroCode = $exception->errorInfo[1];
            if($errorCode == 1451){
                return $this->errorResponse('Cannot remove this resource permanently. It is related with any other resource',409);
            }
        }

        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }
        if (config('app.debug')) {
            return parent::render($request, $exception);            
        }
        return $this->errorResponse('Falla inesperada. Intente luego', 500);

        return parent::render($request, $exception);
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('Unauthenticated.',401);
    }


    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        
        $errors = $e->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($errors, 422) : redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($errors);
        }
        return $this->errorResponse($errors, 422);

        
    }
    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
