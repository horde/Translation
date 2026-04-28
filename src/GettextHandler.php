<?php

/**
 * @package Translation
 *
 * Copyright 2010-2026 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */
declare(strict_types=1);

namespace Horde\Translation;

use InvalidArgumentException;

/**
 * Gettext-style translation handler using simple string lookup and
 * plural selection.
 *
 * Delegates catalog retrieval to a Storage backend. Defaults to
 * GettextMoStorage for backwards compatibility.
 *
 * @author  Jan Schneider <jan@horde.org>
 * @package Translation
 */
class GettextHandler implements Handler
{
    /**
     * The translation domain, e.g. package name.
     */
    protected string $domain;

    /**
     * The storage backend.
     */
    protected Storage $storage;

    /**
     * @param string $domain        The translation domain, e.g. package name.
     * @param string $path          The path to the gettext catalog.
     * @param Storage|null $storage  Storage backend. Defaults to GettextMoStorage.
     */
    public function __construct(string $domain, string $path, ?Storage $storage = null)
    {
        $this->domain = $domain;
        $this->storage = $storage ?? new GettextMoStorage($domain, $path);
    }

    /**
     * Returns the translation of a message.
     *
     * @param string $message  The string to translate.
     *
     * @return string  The string translation, or the original string if no
     *                 translation exists.
     */
    public function t(string $message): string
    {
        return $this->storage->get($message) ?? $message;
    }

    /**
     * Returns the plural translation of a message.
     *
     * @param string $singular  The singular version to translate.
     * @param string $plural    The plural version to translate.
     * @param int $number       The number that determines singular vs. plural.
     *
     * @return string  The string translation, or the original string if no
     *                 translation exists.
     */
    public function ngettext(string $singular, string $plural, int $number): string
    {
        return $this->storage->getPlural($singular, $plural, $number)
            ?? ($number === 1 ? $singular : $plural);
    }

    /**
     * ICU MessageFormat is not supported by this handler.
     *
     * Returns the message unchanged.
     */
    public function format(string $message, array $params = [], ?string $locale = null): string
    {
        return $message;
    }
}
