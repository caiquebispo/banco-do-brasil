<?php

namespace CaiqueBispo\BancoDoBrasil\Laravel;

use CaiqueBispo\BancoDoBrasil\BancoDoBrasil;
use CaiqueBispo\BancoDoBrasil\BancoDoBrasilConfig;
use CaiqueBispo\BancoDoBrasil\Webhook\WebhookHandler;
use Illuminate\Support\ServiceProvider;

class BancoDoBrasilServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/banco-do-brasil.php' => config_path('banco-do-brasil.php'),
        ], 'banco-do-brasil-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/banco-do-brasil.php', 'banco-do-brasil');

        $this->app->singleton(BancoDoBrasilConfig::class, function ($app) {
            return new BancoDoBrasilConfig($app['config']->get('banco-do-brasil'));
        });

        $this->app->singleton(BancoDoBrasil::class, function ($app) {
            return new BancoDoBrasil($app->make(BancoDoBrasilConfig::class));
        });

        $this->app->alias(BancoDoBrasil::class, 'banco-do-brasil');

        $this->app->singleton(WebhookHandler::class, function ($app) {
            $secret = $app['config']->get('banco-do-brasil.webhook.secret');

            return new WebhookHandler($secret);
        });
    }
}
