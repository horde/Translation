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

/**
 * The Horde_Translation_Handler interface defines the interface for any
 * classes providing translations.
 *
 * @author  Jan Schneider <jan@horde.org>
 * @package Translation
 */
interface Handler
{
    /**
     * Returns the translation of a message.
     *
     * @param string $message  The string to translate.
     *
     * @return string  The string translation, or the original string if no
     *                 translation exists.
     */
    public function t(string $message): string;

    /**
     * Returns the plural translation of a message.
     *
     * @param string $singular  The singular version to translate.
     * @param string $plural    The plural version to translate.
     * @param int $number   The number that determines singular vs. plural.
     *
     * @return string  The string translation, or the original string if no
     *                 translation exists.
     */
    public function ngettext(string $singular, string $plural, int $number): string;

    /**
     * Translates and formats an ICU MessageFormat string.
     *
     * Handlers that do not support ICU formatting return $message unchanged.
     *
     * @param string $message            The ICU message pattern (or message key).
     * @param array<string, mixed> $params  Named parameters for ICU formatting.
     * @param string|null $locale        Locale for number/date/plural formatting.
     *
     * @return string  The formatted translation, or the original string if
     *                 this handler does not support ICU formatting.
     */
    public function format(string $message, array $params = [], ?string $locale = null): string;
}
