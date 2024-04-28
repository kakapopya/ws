<?php
namespace Lambq\Ws;

use Config;
use WhatsProt;
use Illuminate\Support\ServiceProvider;

class WhatsapiServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot()
    {
        $this->publishConfigFiles();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerWhatsProt();
        $this->registerEventListener();
        $this->registerMediaManager();
        $this->registerMessageManager();
        $this->registerSessionManager();
        $this->registerRegistrationTool();
        $this->registerWs();

        $this->mergeConfigFrom(__DIR__ . '/Config/config.php', 'Ws');
    }

    private function publishConfigFiles()
    {
        $this->publishes([
            __DIR__.'/Config/config.php' => config_path('Ws.php'),
        ], 'config');
    }

    private function registerWhatsProt()
    {
        // Set up how the create the WhatsProt object when using MGP25 fork
        $this->app->singleton('WhatsProt', function ()
        {
            // Setup Account details.
            $debug     = Config::get("Ws.debug");
            $log       = Config::get("Ws.log");
            $storage   = Config::get("Ws.data-storage");
            $account   = Config::get("Ws.default");
            $nickname  = Config::get("Ws.accounts.$account.nickname");
            $number    = Config::get("Ws.accounts.$account.number");
            $nextChallengeFile = $storage . "/phone-" . $number . "-next-challenge.dat";

            $whatsProt =  new WhatsProt($number, $nickname, $debug, $log, $storage);
            $whatsProt->setChallengeName($nextChallengeFile);

            return $whatsProt;
        });
    }

    private function registerEventListener()
    {
        $this->app->singleton('Lambq\Ws\Events\Listener', function($app)
        {
            $session = $app->make('Lambq\Ws\Sessions\SessionInterface');

            return new \Lambq\Ws\Events\Listener($session, Config::get('Ws'));
        });
    }

    private function registerMediaManager()
    {
        $this->app->singleton('Lambq\Ws\Media\Media', function($app)
        {
            return new \Lambq\Ws\Media\Media(Config::get('Ws.data-storage') . '/media');
        });
    }

    private function registerMessageManager()
    {
        $this->app->singleton('Lambq\Ws\MessageManager', function($app)
        {
            $media = $app->make('Lambq\Ws\Media\Media');

            return new \Lambq\Ws\MessageManager($media);
        });
    }

    private function registerSessionManager()
    {
        $this->app->singleton('Lambq\Ws\Sessions\SessionInterface', function ($app)
        {
             return $app->make('Lambq\Ws\Sessions\Laravel\Session');
        });
    }

    private function registerWs()
    {
        $this->app->singleton('Lambq\Ws\Contracts\WsInterface', function ($app)
        {
             // Dependencies
             $whatsProt = $app->make('WhatsProt');
             $manager = $app->make('Lambq\Ws\MessageManager');
             $session = $app->make('Lambq\Ws\Sessions\SessionInterface');
             $listener = $app->make('Lambq\Ws\Events\Listener');

             $config = Config::get('Ws');

             return new \Lambq\Ws\Clients\MGP25($whatsProt, $manager, $listener, $session, $config);
        });

    }

    private function registerRegistrationTool()
    {
        $this->app->singleton('Lambq\Ws\Contracts\WsToolInterface', function($app)
        {
            $listener = $app->make('Lambq\Ws\Events\Listener');

            return new \Lambq\Ws\Tools\MGP25($listener, Config::get('Ws.debug'), Config::get('Ws.data-storage'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Lambq\Ws\Contracts\WsInterface', 'Lambq\Ws\Contracts\WsToolInterface'];
    }
}