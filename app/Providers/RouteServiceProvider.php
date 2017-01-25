<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        // Model Bindings
        Route::model('event', \App\Models\Event::class);
        Route::model('person', \App\Models\Person::class);
        Route::model('account', \App\Models\Account::class);
        Route::model('attendee', \App\Models\Attendee::class);
        Route::model('location', \App\Models\Location::class);

        Route::bind('couple', function ($which) {
            return \App\Models\Couple::unguarded(function() use ($which) {
                return in_array($which, ['bride', 'groom'])
                    ? \App\Models\Couple::firstOrNew(['which' => $which])
                    : null;
            });
        });

        Route::bind('iv_token', function ($token) {
            return \App\Models\Invitation::getInvitationFromToken($token);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
