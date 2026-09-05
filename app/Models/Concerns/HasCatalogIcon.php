<?php

namespace App\Models\Concerns;

trait HasCatalogIcon
{
    public function iconIsImage(): bool
    {
        $icon = (string) ($this->icon ?? '');

        return $icon !== '' && (str_contains($icon, '/') || (bool) preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', $icon));
    }

    public function iconUrl(): ?string
    {
        return $this->iconIsImage() ? asset($this->icon) : null;
    }
}
