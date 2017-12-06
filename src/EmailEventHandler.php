<?php

namespace Philipmorrisp\LaravelExceptionEmailNotification;

use Illuminate\Support\Facades\Mail;
use Exception;
use ReflectionClass;
use Prophecy\Exception\Doubler\ClassNotFoundException;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Philipmorrisp\LaravelExceptionEmailNotification\ExceptionOccured;

class EmailEventHandler
{
    public function __construct()
    {

    }

    /**
     * Sends an email to the developer about the exception.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function exceptionOccurred(Exception $exception)
    {
        try {
            $e = FlattenException::create($exception);

            $handler = new SymfonyExceptionHandler();

            $html = $handler->getHtml($e);

            Mail::queue(new ExceptionOccured($html));
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('email.exceptionOccurred', self::class . '@exceptionOccurred');
    }

    /**
     *
     * Dynamically called methods
     *
     * @param $method
     * @param $parameters
     *
     * @throws ClassNotFoundException
     */
    public function __call($method, $parameters)
    {
        $className = config('email-exceptions.mailable_class_namespace') . ucfirst($method);
        if (class_exists($className)) {
            $mailableReflectionClass = new ReflectionClass($className);
            Mail::queue($mailableReflectionClass->newInstanceArgs($parameters));
        } else {
            throw new ClassNotFoundException('Class [' . $className . '] does not exist', $className);
        }
    }
}