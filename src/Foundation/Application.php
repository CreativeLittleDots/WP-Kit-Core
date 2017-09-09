<?php

namespace WPKit\Foundation;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade;
use WPKit\Config\ConfigServiceProvider;

class Application extends Container
{
	
	/**
     * The loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];
    
	public function __construct()
    {
        $this->registerApplication();
        $this->registerCoreServiceProviders();
    }

    /**
     * Register the Application class into the container,
     * so we can access it from the container itself.
     */
    public function registerApplication()
    {
        // Normally, only one instance is shared into the container.
        static::setInstance($this);
        $this->instance('app', $this);
        $this->instance(ContainerContract::class, $this);
        Facade::setFacadeApplication($this);
    }
    
    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreServiceProviders()
    {
        foreach ([
        	ConfigServiceProvider::class
        ] as $provider) {
            $this->register($provider);
        }
    }
    
    /**
     * Register a service provider with the application.
     *
     * @param \WPKit\Foundation\ServiceProvider|string $provider
     * @param array                                       $options
     * @param bool                                        $force
     *
     * @return \WPKit\Foundation\ServiceProvider
     */
    public function register($provider, array $options = [], $force = false)
    {
        if (!$provider instanceof ServiceProvider) {
            $provider = new $provider($this);
        }
        if (array_key_exists($providerName = get_class($provider), $this->loadedProviders)) {
            return;
        }
        $this->loadedProviders[$providerName] = true;
        $provider->register();
        if (method_exists($provider, 'boot')) {
            $provider->boot();
        }
    }
    
}
    
    