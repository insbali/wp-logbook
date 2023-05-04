<?php

namespace Solvrtech\WPlogbook\Model;

class HealthCheckModel
{

    public const OK = 'ok';
    public const FAILED = 'failed';

    public ?string $key = null;
    public ?string $status = self::FAILED;
    public array $meta = [];

    public function get_key(): ?string
    {
        return $this->key;
    }

    public function set_key(?string $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function get_status(): ?string
    {
        return $this->status;
    }

    public function set_status(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function get_meta(): array
    {
        return $this->meta;
    }

    public function set_meta(array $meta): self
    {
        $this->meta = $meta;
        return $this;
    }
}
