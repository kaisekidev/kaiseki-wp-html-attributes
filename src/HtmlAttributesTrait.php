<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\HtmlAttributes;

use function array_filter;
use function array_key_exists;
use function array_merge;
use function array_push;
use function array_unique;
use function count;
use function esc_attr;
use function esc_url;
use function explode;
use function implode;
use function in_array;
use function ksort;
use function sprintf;
use function trim;
use function wp_allowed_protocols;

trait HtmlAttributesTrait
{
    public function addAttribute(string $name, string $value, bool $merge = true): self
    {
        if ($merge === false) {
            $this->attributes[$name] = $value;

            return $this;
        }
        $this->attributes = $this->mergeAttributes($this->attributes ?? [], [
            $name => $value,
        ]);

        return $this;
    }

    /**
     * @param array<string, string> $attributes
     * @param bool                  $merge
     */
    public function addAttributes(array $attributes, bool $merge = true): self
    {
        if ($merge === false) {
            foreach ($attributes as $name => $value) {
                $this->attributes[$name] = $value;
            }

            return $this;
        }
        $this->attributes = $this->mergeAttributes($this->attributes ?? [], $attributes);

        return $this;
    }

    public function addClass(string ...$class): self
    {
        $classList = [];
        foreach ($class as $value) {
            $classList = [...$classList, ...explode(' ', $value)];
        }
        $this->addAttribute('class', implode(' ', $classList));

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }

    public function getAttribute(string $name): ?string
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * @param array<string, string> $attributes
     */
    public function renderAttributes(array $attributes = []): string
    {
        return $this->attributesToString($this->addAttributes($attributes)->getAttributes());
    }

    /**
     * @param array<string, string> $attributes
     *
     * @return string
     */
    private function attributesToString(array $attributes = []): string
    {
        if ($attributes === []) {
            return '';
        }
        $newAttributes = [];
        foreach ($attributes as $key => $value) {
            if (in_array($key, ['href', 'class', 'target', 'rel'], true) && $value === '') {
                continue;
            }
            if ($key === 'href') {
                $newAttributes[$key] = sprintf('%s="%s"', $key, esc_url($value, ['fb-messenger', ...wp_allowed_protocols()]));

                continue;
            }
            $newAttributes[$key] = sprintf('%s="%s"', $key, esc_attr($value));
        }

        return implode(' ', $this->sortAttributes($newAttributes));
    }

    /**
     * @param array<string, string>... $attributes
     *
     * @return array<string, string>
     */
    private function mergeAttributes(array ...$attributes): array
    {
        $classList = [];
        foreach ($attributes as $attr) {
            if (!array_key_exists('class', $attr) || $attr['class'] === '') {
                continue;
            }
            array_push($classList, ...explode(' ', trim((string)$attr['class'])));
        }
        $newAttributes = array_merge(...$attributes);
        if (count($classList) > 0) {
            $newAttributes['class'] = implode(' ', array_unique(array_filter($classList)));
        }

        return $newAttributes;
    }

    /**
     * @param array<string, string> $attributes
     *
     * @return array<string, string>
     */
    private function sortAttributes(array $attributes = []): array
    {
        if ($attributes === []) {
            return [];
        }
        $order = [
            'id',
            'href',
            'target',
            'rel',
            'class',
        ];
        $sortedAttributes = [];
        foreach ($order as $key) {
            if (!array_key_exists($key, $attributes)) {
                continue;
            }

            $sortedAttributes[$key] = $attributes[$key];
            unset($attributes[$key]);
        }
        ksort($attributes);

        return array_merge(
            $sortedAttributes,
            $attributes
        );
    }
}
