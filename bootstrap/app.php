<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
//  Descomentar para usar o arquivo routes/seguranca.php, caso este projeto necessite deste arquivo
//  Não esqueça de criar o arquivo routes/seguranca.php
        then: function () {
            Route::middleware('seguranca')
                ->group(base_path('routes/seguranca.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        
        // Registar aliases de middleware
        $middleware->alias([
            'session.cliente' => \App\Http\Middleware\SessaoClienteMiddleware::class,
        ]);
        
        $middleware->group('seguranca', [
             \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
//             'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \GapPay\Seguranca\Middleware\RecursoExiste::class,
            \GapPay\Seguranca\Middleware\Autorizacao::class,
            \GapPay\Seguranca\Middleware\CadastroAutomaticoDeTelaEDependencia::class,
            \App\Http\Middleware\MenuMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
