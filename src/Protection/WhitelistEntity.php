<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Protection;

class WhitelistEntity
{
    protected array $entity;

    public function __construct(array $entity)
    {
        $this->entity = $entity;
    }

    public function type(): string
    {
        return $this->entity['type'] ?? '';
    }

    public function ipAddress(): string
    {
        return $this->entity['whitelisted_ip_address'] ?? '';
    }

    /**
     * Could be a person or a external system.
     */
    public function description(): string
    {
        return $this->entity['whitelisted_description'] ?? '';
    }
}
