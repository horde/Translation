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
 * Null translation handler that returns all messages unchanged.
 *
 * Useful as a fallback or for testing when no actual translations
 * are needed.
 *
 * @author    Jan Schneider <jan@horde.org>
 * @category  Horde
 * @copyright 2010-2026 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package   Translation
 */
class NullHandler implements Handler
{
    public function t(string $message): string
    {
        return $message;
    }

    public function ngettext(string $singular, string $plural, int $number): string
    {
        return $number === 1 ? $singular : $plural;
    }

    public function format(string $message, array $params = [], ?string $locale = null): string
    {
        return $message;
    }
}
