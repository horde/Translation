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
 * ICU MessageFormat translation handler.
 *
 * Resolves ICU message patterns via PHP's intl extension
 * (MessageFormatter). The format() method looks up the pattern from
 * a Storage backend and applies named parameters.
 *
 * t() and ngettext() are not this handler's responsibility and return
 * the input unchanged (noop), allowing a ChainHandler to fall through
 * to a gettext-style handler for those calls.
 *
 * @author    Jan Schneider <jan@horde.org>
 * @category  Horde
 * @copyright 2010-2026 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package   Translation
 */
class IcuHandler implements Handler
{
    /**
     * The storage backend for ICU pattern catalogs.
     */
    protected Storage $storage;

    /**
     * Whether the intl extension is available.
     */
    protected bool $intl;

    /**
     * @param string $domain        The translation domain, e.g. package name.
     * @param string $path          The path to the locale directory.
     * @param Storage|null $storage  Storage backend. Defaults to JsonStorage.
     */
    public function __construct(string $domain, string $path, ?Storage $storage = null)
    {
        $this->storage = $storage ?? new JsonStorage($domain, $path);
        $this->intl = class_exists(MessageFormatter::class);
    }

    /**
     * Not this handler's responsibility. Returns input unchanged.
     */
    public function t(string $message): string
    {
        return $message;
    }

    /**
     * Not this handler's responsibility. Returns input unchanged.
     */
    public function ngettext(string $singular, string $plural, int $number): string
    {
        return $number === 1 ? $singular : $plural;
    }

    /**
     * Translates and formats an ICU MessageFormat string.
     *
     * Looks up the message pattern in the storage backend, then
     * applies named parameters via MessageFormatter.
     *
     * @param string $message            The ICU message pattern key.
     * @param array<string, mixed> $params  Named parameters for formatting.
     * @param string|null $locale        Locale for formatting rules.
     *
     * @return string  The formatted translation, or the original message
     *                 if ext-intl is unavailable or no translation exists.
     */
    public function format(string $message, array $params = [], ?string $locale = null): string
    {
        if (!$this->intl) {
            return $message;
        }

        $translated = $this->storage->get($message);
        if ($translated === null) {
            return $message;
        }

        $locale ??= $this->detectLocale();
        $formatter = MessageFormatter::create($locale, $translated);
        if ($formatter === null) {
            return $message;
        }

        $result = $formatter->format($params);
        return $result !== false ? $result : $message;
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
}
