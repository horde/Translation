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

use InvalidArgumentException;

/**
 * Translation storage backend using gettext .mo files.
 *
 * Wraps PHP's gettext extension (bindtextdomain, dgettext, dngettext).
 * Falls back gracefully if the gettext extension is not installed.
 *
 * @author    Jan Schneider <jan@horde.org>
 * @category  Horde
 * @copyright 2010-2026 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package   Translation
 */
class GettextMoStorage implements Storage
{
    /**
     * The translation domain, e.g. package name.
     */
    protected string $domain;

    /**
     * Whether the gettext extension is available.
     */
    protected bool $gettext;

    /**
     * @param string $domain  The translation domain, e.g. package name.
     * @param string $path    The path to the locale directory containing .mo files.
     *
     * @throws InvalidArgumentException  If the path is not a directory.
     */
    public function __construct(string $domain, string $path)
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException("$path is not a directory");
        }
        $this->gettext = function_exists('dgettext');
        if (!$this->gettext) {
            return;
        }
        $this->domain = $domain;
        bindtextdomain($this->domain, $path);
    }

    public function get(string $message): ?string
    {
        if (!$this->gettext) {
            return null;
        }
        $translated = dgettext($this->domain, $message);
        return $translated !== $message ? $translated : null;
    }

    public function getPlural(string $singular, string $plural, int $number): ?string
    {
        if (!$this->gettext) {
            return null;
        }
        $translated = dngettext($this->domain, $singular, $plural, $number);
        $expected = $number === 1 ? $singular : $plural;
        return $translated !== $expected ? $translated : null;
    }
}
