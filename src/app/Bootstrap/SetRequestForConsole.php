<?php

namespace WPKit\Bootstrap;

use Illuminate\Http\Request;
use WPKit\Core\Application;

class SetRequestForConsole
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $url = $app->make('config')->get('app.url', 'http://localhost');

        $app->instance('request', Request::create($url, 'GET', [], [], [], $_SERVER));
    }
}
