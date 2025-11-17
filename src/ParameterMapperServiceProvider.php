<?php

namespace Kjos\ParameterMapper;

use Illuminate\Support\ServiceProvider;
use Kjos\ParameterMapper\Middleware\MapRequestParameters;

class ParameterMapperServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/parameter-mapper.php' => config_path('parameter-mapper.php'),
        ], 'parametermap');
        $this->app['router']->aliasMiddleware('parametermap', MapRequestParameters::class);
    }
}
