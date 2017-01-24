<?php

namespace App\Enhance\Providers;

use Twilio\Rest\Client as Twilio;
use Illuminate\Support\ServiceProvider;

class TwilioServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Twilio::class, function ($app) {
            $config = $app['config']['services']['twilio'];
            return new Twilio($config['sid'], $config['token']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Twilio::class];
    }
}
