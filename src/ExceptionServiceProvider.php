<?php

namespace Philipmorrisp\LaravelExceptionEmailNotification;

use Illuminate\Support\ServiceProvider;
use App\Exceptions\Handler as DefaultExceptionHandler;
use Philipmorrisp\LaravelExceptionEmailNotification\ExceptionHandler as Handler;

class ExceptionServiceProvider extends ServiceProvider
{
    const ESTABLISHED_VIEW_NAMESPACE = '/resources/views/email-exceptions';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->vendorPublish();
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/email-exceptions.php'), 'email-exceptions');
        $this->loadView();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DefaultExceptionHandler::class, Handler::class);
    }

    /**
     * Any folders or files added to the $this->publishes array in the boot() method
     * will be published when you run the vendor:publish command.
     *
     * @return void
     */
    private function vendorPublish(){
        $this->publishes([
            __DIR__.'/ExceptionOccured.php' => app_path('Mail/ExceptionOccured.php'),
            __DIR__.'/views/exception.blade.php' => resource_path('views/email-exceptions/exception.blade.php'),
            __DIR__ . '/../config/email-exceptions.php' => config_path('email-exceptions.php')
        ], 'email-exception-template');
    }

    /**
     * Package views can still be accessed via a namespace.
     * In lieu of the old view:publish command, you should add your views to the publishes array.
     *
     * The loadViewsFrom function allows you to register the view namespace,
     * however you may want to check to see if the views have been published before you create the namespace
     *
     * @return void
     */
    private function loadView(){
        // Establish Views Namespace
        if (is_dir(base_path() . self::ESTABLISHED_VIEW_NAMESPACE)) {
            // The package views have been published - use those views.
            $this->loadViewsFrom(base_path() . self::ESTABLISHED_VIEW_NAMESPACE, 'email-exceptions-view');
        } else {
            // The package views have not been published. Use the defaults.
            $this->loadViewsFrom(__DIR__ . '/views', 'email-exceptions-view');
        }
    }
}