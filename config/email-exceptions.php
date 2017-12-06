<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MAILABLE_CLASS_NAMESPACE_PREFIX
    |--------------------------------------------------------------------------
    */
    'mailable_class_namespace' => env('MAILABLE_CLASS_NAMESPACE', 'App\Mail\\'),

    /*
    |--------------------------------------------------------------------------
    | Email Exception Enabled
    |--------------------------------------------------------------------------
    |
    | Enable/Disable notifications
    |
    */

    'enabled' => env('EMAIL_EXCEPTION_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Email Exception From
    |--------------------------------------------------------------------------
    |
    | This is the email your exception will be from.
    |
    */

    'from' => env('EMAIL_EXCEPTION_FROM', 'demo@exmaple.com'),
    'from_name' => env('EMAIL_EXCEPTION_FROM_NAME', 'abc'),

    /*
    |--------------------------------------------------------------------------
    | Email Exception To
    |--------------------------------------------------------------------------
    |
    | This is the email(s) the exceptions will be emailed to.
    |
    */

    'to' => env('EMAIL_EXCEPTION_TO', 'demo@example.com'),

    /*
    |--------------------------------------------------------------------------
    | Email Exception CC
    |--------------------------------------------------------------------------
    |
    | This is the string of email(s) with ',' delimiter on the exceptions will be CC emailed to.
    |
    */

    'cc' => env('EMAIL_EXCEPTION_CC', null),

    /*
    |--------------------------------------------------------------------------
    | Email Exception BCC
    |--------------------------------------------------------------------------
    |
    | This is the string of email(s) with ',' delimiter on the exceptions will be BCC emailed to.
    |
    */

    'bcc' => env('EMAIL_EXCEPTION_BCC', null),

    /*
    |--------------------------------------------------------------------------
    | Email Exception Subject
    |--------------------------------------------------------------------------
    |
    | This is the subject of the exception email
    |
    */

    'subject' => env('EMAIL_EXCEPTION_SUBJECT', 'Error on '.config('app.env')),
];




