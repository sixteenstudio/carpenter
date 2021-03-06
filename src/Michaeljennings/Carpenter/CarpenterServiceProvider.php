<?php namespace Michaeljennings\Carpenter;

use Illuminate\Support\ServiceProvider;

class CarpenterServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../views/', 'michaeljennings/carpenter');
        $this->publishes([__DIR__.'/../../config/config.php' => config_path('carpenter.php')]);
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'carpenter');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDrivers();

        $this->app->bind('michaeljennings.carpenter.driverContainer', function($app)
        {
            return new DriverContainer([
                'db' => $app['michaeljennings.carpenter.database']->driver(),
                'paginator' => $app['michaeljennings.carpenter.paginator']->driver(),
                'session' => $app['michaeljennings.carpenter.session']->driver(),
                'view' => $app['michaeljennings.carpenter.view']->driver(),
            ]);
        });

        $this->app->singleton('michaeljennings.carpenter', function($app)
        {
            return new Carpenter(
                $app['michaeljennings.carpenter.driverContainer'],
                $this->app['config']['carpenter']
            );
        });
    }

    /**
     * Register the carpenter drivers.
     *
     * @return void
     */
    private function registerDrivers()
    {
        $this->app->bind('michaeljennings.carpenter.database', function($app)
        {
            return new Database\DatabaseManager($app);
        });

        $this->app->bind('michaeljennings.carpenter.paginator', function($app)
        {
            return new Pagination\PaginationManager($app);
        });

        $this->app->bind('michaeljennings.carpenter.session', function($app)
        {
            return new Session\SessionManager($app);
        });

        $this->app->bind('michaeljennings.carpenter.view', function($app)
        {
            return new View\ViewManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
