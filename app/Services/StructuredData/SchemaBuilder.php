<?php

namespace App\Services\StructuredData;

abstract class SchemaBuilder
{
    protected array $data = [];

    /**
     * Build and return the schema array
     */
    abstract public function build(): array;

    /**
     * Convert schema to JSON-LD string
     */
    public function toJson(): string
    {
        return json_encode($this->build(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Convert schema to script tag
     */
    public function toScript(): string
    {
        return '<script type="application/ld+json">'.$this->toJson().'</script>';
    }

    /**
     * Set context
     */
    protected function setContext(string $context = 'https://schema.org'): self
    {
        $this->data['@context'] = $context;

        return $this;
    }

    /**
     * Set type
     */
    protected function setType(string $type): self
    {
        $this->data['@type'] = $type;

        return $this;
    }

    /**
     * Add data to schema
     */
    protected function add(string $key, $value): self
    {
        if ($value !== null && $value !== '' && $value !== []) {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Make URL absolute
     */
    protected function url(string $path): string
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return url($path);
    }

    /**
     * Get asset URL (respects ASSET_URL / N0C).
     */
    protected function asset(string $path): string
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }
}
