<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

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
    public function boot() {
        
        //

        parent::boot();

        Route::bind('campaign', function ($value) {
            return \App\Campaign::where('id', $value)->where('user_id',\Auth::user()->id)->first() ?? abort(404);
        });
        Route::bind('keyword', function ($value) {
            $campaigns=\Auth::user()->campaigns->pluck('id')->toArray();
            return \App\Keyword::where('id', $value)->whereIn('campaign_id',$campaigns)->first() ?? abort(404);
        });
        Route::bind('template', function ($value) {
            return \App\Template::where('id', $value)->where('user_id',\Auth::user()->id)->first() ?? abort(404);
        });
        Route::bind('imap-account', function ($value) {
            return \App\ImapAccount::where('id', $value)->where('user_id',\Auth::user()->id)->first() ?? abort(404);
        });
        Route::bind('excluded-domain', function ($value) {
            $campaigns=\Auth::user()->campaigns->pluck('id')->toArray();
            return \App\ExcludedDomain::where('id', $value)->whereIn('campaign_id',$campaigns)->first() ?? abort(404);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map() {
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
    protected function mapWebRoutes() {
        Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes() {
        Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
    }

}
