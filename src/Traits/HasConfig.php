<?php

namespace Krbaidik\AdBsConverter\Traits;

trait HasConfig
{
    /**
     * Get config value with optional fallback
     */
    protected function getConfig(string $key, $default = null)
    {
        return config("nepali-date.$key", $default);
    }

    /**
     * Get all supported locales
     */
    protected function getSupportedLocales(): array
    {
        return array_keys($this->getConfig('locales', []));
    }

    /**
     * Get default locale from config
     */
    protected function getDefaultLocale(): string
    {
        return $this->getConfig('default_locale', 'np');
    }
}