<?php

namespace JustBetter\StatamicPartytown;

use Http\Client\Exception\TransferException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use JustBetter\StatamicPartytown\Partytown;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    public function register(): void
    {
        parent::register();
    }

    public function boot(): void
    {
        $this->bootConfig()
            ->bootViews()
            ->bootRoutes()
            ->bootPublishables();

        parent::boot();
    }

    protected function registerBindings(): self
    {
        $this->app->singleton(Partytown::class, Partytown::class);

        return $this;
    }

    public function bootConfig(): static
    {
        $this->mergeConfigFrom(__DIR__.'/../config/statamic/partytown.php', 'statamic.partytown');

        $this->publishes([
            __DIR__.'/../config/statamic/partytown.php' => config_path('statamic/partytown.php'),
        ], 'config');

        return $this;
    }

    public function bootViews(): static
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'statamic-partytown');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/statamic-partytown'),
        ], 'views');

        return $this;
    }

    public function bootRoutes(): static
    {
        Route::prefix('partytown')->group(function () {
            Route::any('proxy/{url}', function (Request $request, $url) {
                // On nginx we can replace this with https://serverfault.com/a/744626
                // As a result we could also cache the scripts returned https://www.nginx.com/resources/wiki/start/topics/examples/reverseproxycachingexample/
                abort_if(!$url || !in_array(parse_url($url, PHP_URL_HOST), config('statamic.partytown.domain_whitelist')), 404);

                try  {
                    return Http::retry(3, 100, null, false)->send($request->method(), $url, ['query' => $request->query()])->toPsrResponse();
                } catch (TransferException|HttpClientException $e) {
                    // Catch client errors to prevent error reporting, as marketing sites are notorious for timeouts, dropped connections, etc.
                    abort(404);
                }
            })->where('url', '.+')->name('statamic-partytown::proxy');
        });

        return $this;
    }

    public function bootPublishables(): static
    {
        $this->publishes([
            __DIR__.'/../resources/blueprints/globals' => resource_path('blueprints/globals'),
            __DIR__.'/../resources/content/globals' => base_path('content/globals'),
        ], 'statamic-content');

        return $this;
    }
}
