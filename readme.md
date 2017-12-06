#   Laravel-Exception-Email-Notification

*   An enhanced exception handler with send email Event. 
*   Job Queue is implemented to avoid the additional loading time to send notification email.
*   An EmailEventHandler is introduced with dynamic subscribe method.
*   Part of JSON Response has been written for manipulating Exception
*   You must have a running [mail server](https://laravel.com/docs/5.4/mail) to go further

##  Install
Requires Laravel 5 - 5.4. Laravel 5.5 is not tested but you could give it a try.

```
composer install philipmorrisp/laravel-exception-email-notification dev-master
```


You can skip adding provider if you can load the [laravel package auto-discovery](https://laravel-news.com/package-auto-discovery).
 
Still it will be needed to add if we cannot depend on composer (e.g. On production without composer installed) 

in the providers array of `config/app.php`:      

```
# config/app.php

Philipmorrisp\LaravelExceptionEmailNotification\ExceptionServiceProvider::class,
```


Publish files to app for more custom configuration. (optional) 
```
php artisan vendor:publish --provider="Philipmorrisp\LaravelExceptionEmailNotification\ExceptionServiceProvider"
```

Reload code
```
composer dump-auto
php artisan config:cache
```

Add this code to `app/Providers/EventServiceProvider.php`, if you do not have this file, create the file as provided:
```
#app/Providers/EventServiceProvider.php

protected $subscribe = [
    ...
    'App\Handlers\EmailEventHandler',
];

--------

# The following will be required ONLY if you do not have app/Providers/EventServiceProvider.php
# Add the following in the providers array of config/app.php:

App\Providers\EventServiceProvider::class,

# Create app/Providers/EventServiceProvider.php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    protected $subscribe = [
        'Philipmorrisp\LaravelExceptionEmailNotification\EmailEventHandler',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
```

Add this in the bottom of `.env`. Configure the settings
```
EMAIL_EXCEPTION_ENABLED=false
EMAIL_EXCEPTION_FROM=name@domain.com
EMAIL_EXCEPTION_FROM_NAME='ABC'
EMAIL_EXCEPTION_TO='name@domain.com,name@domain.com'
EMAIL_EXCEPTION_CC=
EMAIL_EXCEPTION_BCC=
EMAIL_EXCEPTION_SUBJECT=
```

The sending exception email used default [Job Queue](https://laravel.com/docs/5.4/queues).
```
# .env
QUEUE_DRIVER=database

# Configure settings in config/queue.php > connection > database
    ...
    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
    ],
    ...

# Run the following command

php artisan config:cache
php artisan queue:table
php artisan migrate

# Listen to the queue
php artisan queue:work database --daemon --tries=3
```

##  Advanced Use
If you want to use `EmailEventHandler.php` for handling send email using Event in the future,
create `app/Handlers/EmailEventHandler.php`.

Then you must change to use `App\Handlers\EmailEventHandler` in the `app/Providers/EventServiceProvider.php` instead.
```
# app/Handlers/EmailEventHandler.php
<?php

namespace App\Handlers;


use Config;
use Exception;
use Philipmorrisp\LaravelExceptionEmailNotification\EmailEventHandler as BaseEmailEventHandler;

class EmailEventHandler extends BaseEmailEventHandler
{
    /**
     * @param $events
     */
    public function subscribe($events)
    {
        //Add your custom subscribe events here. 
        //Then the 'eventName' will load app/Mail/MethodName.php 
        //(Capitalized methodName as the mailable php file name and class name) , Mailable Class will be used.
        //You can take a look at app/Mail/ExceptionOccured.php to see how to create a Mailable Queueable class
        
        //Example
        //$events->listen('email.eventName', self::class . '@methodName');

        parent::subscribe($events);
    }

    /**
     *
     * Dynamically called methods
     *
     * @param $method
     * @param $parameters
     */
    public function __call($method, $parameters)
    {
        // Custom code can be written here...
        
        parent::__call($method, $parameters);
    }
}
```

Then you must reload code
```
composer dump-auto
php artisan config:cache
```

To use the event in the subscribe method, apply the following code in your classes:

`\Illuminate\Support\Facades\Event::fire('email.eventName');`



##  TODO:
*   Finish the part of JSON Response on Exceptions or integrate with other laravel packages

##  Reference Links
-   [Package Development](http://stagerightlabs.com/blog/laravel5-pacakge-development-service-provider)