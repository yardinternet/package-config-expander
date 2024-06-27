<?php

namespace Yard\ConfigExpander\Traits;

trait Route
{
    public function route(string $path): string
    {
        return config('app.url') . $path;
    }
}
