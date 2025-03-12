<?php
namespace Hitaaksata\Bpjs;

use Illuminate\Support\ServiceProvider;

class BpjsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
       $this->publishes([
            __DIR__.'../../../config/antrol.php' => config_path('antrol.php'),
        ], 'config');
    }
}