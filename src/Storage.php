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

/**
 * Storage interface for translation catalog backends.
 *
 * Storage is responsible for retrieving translated strings from a
 * catalog (files, database, etc). It does NOT apply formatting or
 * plural rule selection beyond what the underlying backend provides
 * natively (e.g. gettext handles plural rules internally).
 *
 * @author    Jan Schneider <jan@horde.org>
 * @category  Horde
 * @copyright 2010-2026 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package   Translation
 */
interface Storage
{
    /**
     * Returns the translation of a message.
     *
     * @param string $message  The string to translate (source language key).
     *
     * @return string|null  The translated string, or null if no translation exists.
     */
    public function get(string $message): ?string;

    /**
     * Returns the plural translation of a message.
     *
     * @param string $singular  The singular version to translate.
     * @param string $plural    The plural version to translate.
     * @param int $number       The number that determines singular vs. plural.
     *
     * @return string|null  The translated string, or null if no translation exists.
     */
    public function getPlural(string $singular, string $plural, int $number): ?string;
}
