<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\HtmlAttributes;

class HtmlAttributes
{
    use HtmlAttributesTrait;

    /** @var array<string, string> */
    private array $attributes;

    /**
     * @param array<string, string>|null $attributes
     *
     * @return self
     */
    public static function create(?array $attributes = []): self
    {
        return new self($attributes);
    }

    /**
     * @param array<string, string> $attributes
     */
    public function __construct(?array $attributes = [])
    {
        $this->attributes = $attributes ?? [];
    }
}
