<?php

namespace GapPay\Seguranca\Providers;

use Illuminate\Support\ServiceProvider;
use GapPay\Seguranca\Commands\ModoDesenvolvimento;
use GapPay\Seguranca\Commands\ModoProducao;

class SegurancaServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'seguranca');

        //php artisan vendor:publish --tag=tabelas-seguranca
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations')
        ], 'tabelas-seguranca');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ModoDesenvolvimento::class,
                ModoProducao::class
            ]);
        }
    }
}
