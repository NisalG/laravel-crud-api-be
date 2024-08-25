<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Console\Scheduling\Schedule;

use App\Exceptions\Handler;
use App\Exceptions\CustomExceptionHandler;

use App\Http\Middleware\RoleManagement;
use App\Http\Middleware\LogUserActivity;
use App\Http\Middleware\Localization;
use App\Jobs\DeleteOldPostsJob;
use App\Mail\DailyPostCountEmail;
use Illuminate\Support\Facades\Mail;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        /**Customizes the CSRF token validation middleware. 
         * It specifies that CSRF token validation should be applied to all routes 
         * except those that match the pattern stripe/*.*/
        $middleware->validateCsrfTokens(
            except: ['stripe/*']
        );

        // Registering custom middlewares - Without an alias
        $middleware->append(RoleManagement::class);
        $middleware->append(LogUserActivity::class);

        // Registering custom middlewares - With an alias and grouping
        $middleware->alias([
            'localize' => Localization::class
        ]);
    })   
    // Register the custom exception handler - Laravel 11 method
    ->withExceptions(function (Exceptions $exceptions) {
        return [
            App\Exceptions\EntityNotFoundException::class => function ($e, $request) {
                return response()->json(['message' => 'Entity not found'], 404);
            },
            App\Exceptions\ValidationException::class => function ($e, $request) {
                return response()->json(['errors' => $e->errors()], 422);
            },
            App\Exceptions\AuthorizationException::class => function ($e, $request) {
                return response()->json(['message' => 'Unauthorized'], 401);
            },
            App\Exceptions\DatabaseException::class => function ($e, $request) {
                return response()->json(['message' => 'Database error'], 500);
            },
        ];
    })
    // Laravel 10 method
    // $app->singleton(
    //     ExceptionHandler::class,
    //     CustomExceptionHandler::class
    // );

    //define your scheduled tasks
    //According to the documentation, it is better to be done in here rather than in routes\console.php for clarity
    // Couldn't find a working example to pass params for this, therefore use routes\console.php one 

    // ->withSchedule(function (Schedule $schedule, $to, $emailData) {
    //     $schedule->call( function ($to, $emailData) {
    //         // Mail::to($to)->send(new DailyPostCountEmail($emailData));
    //         Mail::to($to)->send(new DailyPostCountEmail($emailData));
    //     })->everyMinute();
    // })

    // create the application instance
    ->create();
    
return $app;
