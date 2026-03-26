<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Line\Provider as LineProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class SocialiteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['events']->listen(
            SocialiteWasCalled::class,
            fn(SocialiteWasCalled $event) => $event->extendSocialite('line', LineProvider::class)
        );
    }
}
