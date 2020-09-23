<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            return $this->renderHttpException($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render http exception
     *
     * @param HttpException $exception
     * @return JsonResponse
     */
    private function renderHttpException(HttpException $exception): JsonResponse
    {
        try {
            $className = (new \ReflectionClass($exception))->getShortName();
        } catch (\ReflectionException $e) {
            return new JsonResponse(['error' => 'Server error.', 500]);
        }

        return new JsonResponse([
            'error' => $this->resolveErrorMessage($exception->getMessage(), $className),
        ], $exception->getStatusCode());
    }

    /**
     * Resolve error message
     *
     * @param string|null $error
     * @param string $classname
     * @return string
     */
    private function resolveErrorMessage(?string $error, string $classname): string
    {
        if(!empty($error)) {
            return $error;
        }

        $exception = str_replace('HttpException','',$classname);
        $default = preg_replace('/(?<!^)[A-Z]/', ' $0', $exception);

        return ucfirst(strtolower($default)).'.';
    }
}
