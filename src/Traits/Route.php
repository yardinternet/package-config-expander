<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Traits;

trait Route
{
    public function route(string $path): string
    {
        return $this->composeBaseURL() . $path;
    }

    /**
     * Constructs the base URL of the main site, ensuring that subdirectories load
     * their assets from the main site.
     */
    private function composeBaseURL(): string
    {
        $fullURL = home_url();
        $components = parse_url($fullURL);
        $baseURL = sprintf('%s://%s', $components['scheme'], $components['host']);

        if (isset($components['port'])) {
            $baseURL .= ':' . $components['port'];
        }

        return $baseURL;
    }
}
