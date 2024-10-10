<?php

namespace App\Providers;

use App\Contracts\LeagueCreationServiceInterface;
use App\Contracts\MatchGenServiceInterface;
use App\Contracts\MatchSimServiceInterface;
use App\Contracts\RoundCalculatorServiceInterface;
use App\Services\LeagueCreationService;
use App\Services\LeagueService;
use App\Services\MatchGenService;
use App\Services\MatchSimService;
use App\Services\RoundCalculatorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LeagueCreationServiceInterface::class, LeagueCreationService::class);
        $this->app->bind(MatchGenServiceInterface::class, MatchGenService::class);
        $this->app->bind(MatchSimServiceInterface::class, MatchSimService::class);
        $this->app->bind(RoundCalculatorServiceInterface::class, RoundCalculatorService::class);



    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
