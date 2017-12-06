<?php

namespace Philipmorrisp\LaravelExceptionEmailNotification;

use Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\AuthenticationException;
use App\Exceptions\Handler as DefaultExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHandler extends DefaultExceptionHandler
{
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
        if ($this->shouldReport($exception)) {
            if (config('app.mail_exception_enable')) {
                Event::fire('email.exceptionOccurred', $exception); // sends an email
            }
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        $exceptionClass = get_class($exception);

        switch ($exceptionClass) {
            case TokenMismatchException::class:
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'token_error'
                    ], $exception->getStatusCode());
                };
                return redirect('/')->withErrors(['token_error' => 'Sorry, your session seems to have expired. Please try again.']);
            default:
                break;
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
