<?php
/**
 * Copyright 2010-2017 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @category  Horde
 * @copyright 2010-2017 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package   Translation
 */
declare(strict_types=1);

namespace Horde\Translation;

use InvalidArgumentException;

/**
 * Horde_Translation is the base class for any translation wrapper classes in
 * libraries that want to utilize the Horde_Translation library for
 * translations.
 *
 * @author    Jan Schneider <jan@horde.org>
 * @category  Horde
 * @copyright 2010-2017 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package   Translation
 */
abstract class AbstractTranslation implements Translation
{
    /**
     * The translation domain, e.g. the library name, for the default gettext
     * handler.
     *
     * @var string
     */
    protected static string $domain;

    /**
     * The relative path to the translations for the default gettext handler.
     *
     * @var string
     */
    protected static string $directory;

    /**
     * The handlers providing the actual translations.
     *
     * @var Handler[]
     */
    protected static array $handlers = [];

    /**
     * Loads a translation handler class pointing to the library's translations
     * and assigns it to $handler.
     *
     * @param string $handlerClass  The name of a class implementing the
     *                              Horde_Translation_Handler interface.
     * @throws TranslationException
     */
    public static function loadHandler(string $handlerClass): void
    {
        if (!is_subclass_of($handlerClass, Handler::class, true)) {
            $message = 'Cannot load this class as a handler: ' . $handlerClass;
            throw new TranslationException($message, 0);
        }
        if (!static::$domain || !static::$directory) {
            throw new TranslationException('The domain and directory properties must be set by the class that extends Horde\Translation\AbstractTranslation.');
        }
        static::setHandler(static::$domain, new $handlerClass(static::$domain, static::$directory));
    }

    /**
     * Assigns a translation handler object to $handlers.
     *
     * Type hinting isn't used on purpose. You should extend a custom
     * translation handler passed here from the Horde_Translation interface,
     * but technically it's sufficient if you provide the API of that
     * interface.
     *
     * @param string $domain                      The translation domain.
     * @param Handler $handler  An object implementing the
     *                                            Horde_Translation_Handler
     *                                            interface.
     */
    public static function setHandler(string $domain, Handler $handler): void
    {
        static::$handlers[$domain] = $handler;
    }

    /**
     * Returns the translation of a message.
     *
     * @param string $message  The string to translate.
     *
     * @return string  The string translation, or the original string if no
     *                 translation exists.
     */
    public static function t(string $message): string
    {
        if (!isset(static::$handlers[static::$domain])) {
            static::loadHandler(GettextHandler::class);
        }
        return static::$handlers[static::$domain]->t($message);
    }

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
    public static function ngettext(string $singular, string $plural, int $number): string
    {
        if (!isset(static::$handlers[static::$domain])) {
            static::loadHandler(GettextHandler::class);
        }
        return static::$handlers[static::$domain]->ngettext($singular, $plural, $number);
    }

    /**
     * Allows a gettext string to be defined and recognized as a string by
     * the horde translation utilities, but no translation is actually
     * performed (raw gettext = r()).
     *
     * @since 2.1.0
     *
     * @param string $message  The raw string to mark for translation.
     *
     * @return string  The raw string.
     */
    public static function r(string $message): string
    {
        return $message;
    }
}
