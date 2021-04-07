<?php

namespace Creacoon\JiraTile;

use Spatie\Dashboard\Models\Tile;

class JiraStore
{
    private Tile $tile;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $this->tile = Tile::firstOrCreateForName("JiraTile");
    }

    public function setData(array $data): self
    {
        $this->tile->putData('JiraInProgressStore', $data);

        return $this;
    }

    public function getData(): array
    {
        return$this->tile->getData('JiraInProgressStore') ?? [];
    }
}
