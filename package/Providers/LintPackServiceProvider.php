<?php

namespace JakubSzajna\LintPack\Providers;

use Illuminate\Support\ServiceProvider;

class LintPackServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/lint.php', 'lint'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/lint.php' => config_path('lint.php'),
        ]);
    }
}