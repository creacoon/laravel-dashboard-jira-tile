<?php

namespace Creacoon\JiraTile;

use Livewire\Component;

class JiraTileComponent extends Component
{
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }
    
    
    public function render()
    {
        return view('dashboard-jira-tile::tile', [
            'refreshIntervalInSeconds' => config('dashboard.tiles.jira.refresh_interval_in_seconds') ?? 60,
            'jiraData' => JiraStore::make()->getData(),
        ]);
    }
}
