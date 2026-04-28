<?php

/**
 * Copyright 2010-2026 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @category  Horde
 * @copyright 2010-2026 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package   Translation
 */
declare(strict_types=1);

namespace Horde\Translation;

use MessageFormatter;

/**
 * Translation storage backend using JSON files.
 *
 * Reads translation catalogs from JSON files organized in the same
 * directory structure as gettext:
 *   {path}/{locale}/LC_MESSAGES/{domain}.json
 *
 * JSON format: keys are source-language strings (msgid equivalent),
 * values are translated strings. For plurals, values may be ICU
 * MessageFormat patterns resolved via ext-intl when available.
 *
 * @author    Jan Schneider <jan@horde.org>
 * @category  Horde
 * @copyright 2010-2026 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package   Translation
 */
class JsonStorage implements Storage
{
    /**
     * Cached translation catalog (key => translated value).
     *
     * @var array<string, string>|null
     */
    protected ?array $catalog = null;

    /**
     * The translation domain, e.g. package name.
     */
    protected string $domain;

    /**
     * The path to the locale directory.
     */
    protected string $path;

    /**
     * @param string $domain  The translation domain, e.g. package name.
     * @param string $path    The path to the locale directory.
     */
    public function __construct(string $domain, string $path)
    {
        $this->domain = $domain;
        $this->path = $path;
    }

    public function get(string $message): ?string
    {
        $catalog = $this->loadCatalog();
        return $catalog[$message] ?? null;
    }

    public function getPlural(string $singular, string $plural, int $number): ?string
    {
        $catalog = $this->loadCatalog();

        // Try ICU pattern key first (the singular form is typically the key)
        $pattern = $catalog[$singular] ?? null;
        if ($pattern !== null && class_exists(MessageFormatter::class)) {
            $locale = $this->detectLocale();
            $formatter = MessageFormatter::create($locale, $pattern);
            if ($formatter !== null) {
                $result = $formatter->format(['count' => $number]);
                if ($result !== false) {
                    return $result;
                }
            }
        }

        // Fall back to direct key lookup using the expected form
        $key = $number === 1 ? $singular : $plural;
        return $catalog[$key] ?? null;
    }

    /**
     * Loads and caches the JSON catalog for the current locale.
     *
     * @return array<string, string>
     */
    protected function loadCatalog(): array
    {
        if ($this->catalog !== null) {
            return $this->catalog;
        }

        $locale = $this->detectLocale();
        $file = $this->path . '/' . $locale . '/LC_MESSAGES/' . $this->domain . '.json';

        if (!is_file($file)) {
            // Try with just the language code (e.g. "de" from "de_DE.UTF-8")
            $lang = $this->extractLanguage($locale);
            if ($lang !== $locale) {
                $file = $this->path . '/' . $lang . '/LC_MESSAGES/' . $this->domain . '.json';
            }
        }

        if (!is_file($file)) {
            $this->catalog = [];
            return $this->catalog;
        }

        $content = file_get_contents($file);
        if ($content === false) {
            $this->catalog = [];
            return $this->catalog;
        }

        $decoded = json_decode($content, true);
        $this->catalog = is_array($decoded) ? $decoded : [];
        return $this->catalog;
    }

    /**
     * Detects the current locale from environment variables.
     */
    protected function detectLocale(): string
    {
        foreach (['LC_ALL', 'LC_MESSAGES', 'LANG', 'LANGUAGE'] as $env) {
            $value = getenv($env);
            if ($value !== false && $value !== '' && $value !== 'C' && $value !== 'POSIX') {
                return $value;
            }
        }
        return 'en';
    }

    /**
     * Extracts the language code from a full locale string.
     *
     * "de_DE.UTF-8" => "de"
     * "de_DE" => "de"
     * "de" => "de"
     */
    protected function extractLanguage(string $locale): string
    {
        $parts = explode('.', $locale);
        $parts = explode('_', $parts[0]);
        return $parts[0];
    }
}
