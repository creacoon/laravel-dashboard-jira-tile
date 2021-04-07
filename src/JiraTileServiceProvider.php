<?php

namespace Creacoon\JiraTile;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class JiraTileServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                FetchDataFromJiraCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/creacoon/dashboard-jira-tile'),
        ], 'dashboard-jira-tile-views');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dashboard-jira-tile');

        Livewire::component('jira-tile', JiraTileComponent::class);
    }
}
