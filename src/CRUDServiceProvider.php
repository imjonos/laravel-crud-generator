<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

namespace CodersStudio\CRUD;

use Illuminate\Support\ServiceProvider;

class CRUDServiceProvider extends ServiceProvider
{

    protected $commands = [
        \CodersStudio\CRUD\Console\Commands\CRUDGenerate::class,
        \CodersStudio\CRUD\Console\Commands\CRUDDepInstall::class
    ];
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $mainPath = database_path('migrations');
        $directories = glob($mainPath . '/*' , GLOB_ONLYDIR);
        $paths = array_merge([$mainPath], $directories);
        $this->loadMigrationsFrom($paths);
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'codersstudio.crud');
        $this->loadViewsFrom(resource_path('views/vendor/codersstudio/crud'), 'codersstudio.crud');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'codersstudio.crud');
        $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename){
            require_once($filename);
        }
        $this->mergeConfigFrom(__DIR__.'/../config/crud.php', 'crud');

        // Register the service the package provides.
        $this->app->singleton('crud', function ($app) {
            return new CRUD;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['crud'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/crud.php' => config_path('crud.php'),
        ], 'crud.config');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/codersstudio/crud'),
        ], 'crud.views');

        // Publishing the js.
        $this->publishes([
            __DIR__.'/../resources/js' => base_path('resources/js/vendor/codersstudio/crud'),
        ], 'crud.js');

        // Publishing the sass.
        $this->publishes([
            __DIR__.'/../resources/sass' => base_path('resources/sass/vendor/codersstudio/crud'),
        ], 'crud.sass');

        // Publishing assets.
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/codersstudio'),
        ], 'crud.assets');

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/codersstudio'),
        ], 'crud.views');*/

        // Registering package commands.
        $this->commands($this->commands);
    }
}
