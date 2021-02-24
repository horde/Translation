<?php
/**
 * @package Translation
 *
 * Copyright 2010-2017 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */
declare(strict_types=1);

namespace Horde\Translation;

use InvalidArgumentException;

/**
 * The Horde_Translation_Handler_Gettext provides translations through the
 * gettext extension, but fails gracefully if gettext is not installed.
 *
 * @author  Jan Schneider <jan@horde.org>
 * @package Translation
 */
class GettextHandler implements Handler
{
    /**
     * The translation domain, e.g. package name.
     *
     * @var string
     */
    protected string $domain;

    /**
     * Whether the gettext extension is installed.
     *
     * @var bool
     */
    protected bool $gettext;

    /**
     * Constructor.
     *
     * @param string $domain  The translation domain, e.g. package name.
     * @param string $path    The path to the gettext catalog.
     */
    public function __construct(string $domain, string $path)
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException("$path is not a directory");
        }
        $this->gettext = function_exists('_');
        if (!$this->gettext) {
            return;
        }
        $this->domain = $domain;
        bindtextdomain($this->domain, $path);
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
        return $this->gettext ? dgettext($this->domain, $message) : $message;
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
    public function ngettext(string $singular, string $plural, int $number): string
    {
        return $this->gettext
          ? dngettext($this->domain, $singular, $plural, $number)
          : ($number > 1 ? $plural : $singular);
    }
}
