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
 * @since     2.2.0
 */
declare(strict_types=1);
namespace Horde\Translation;
use ReflectionClass;
/**
 * The Horde_Translation_Autodetect auto detects the locale directory location
 * for the class implementing it.
 *
 * @author    Jan Schneider <jan@horde.org>
 * @category  Horde
 * @copyright 2010-2017 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package   Translation
 * @since     2.2.0
 */
abstract class Autodetect extends AbstractTranslation
{
    /**
     * The absolute PEAR path to the translations for the default gettext handler.
     *
     * This value is automatically set by PEAR Replace Tasks.
     *
     * @var string
     */
    protected static string $pearDirectory;

    /**
     * Auto detects the locale directory location.
     *
     * @param string $handlerClass  The name of a class implementing the
     *                              Handler interface.
     */
    public static function loadHandler(string $handlerClass): void
    {
        if (!static::$domain) {
            throw new TranslationException('The domain property must be set by the class that extends Horde_Translation_Autodetect.');
        }

        $directory = static::searchLocaleDirectory();
        if (!$directory) {
            throw new TranslationException(sprintf('Could not found find any locale directory for %s domain.', static::$domain));
        }

        static::$directory = $directory;
        parent::loadHandler($handlerClass);
    }

    /**
     * Search for the locale directory for different installations methods (eg: PEAR, Composer).
     *
     * @return null|string The directory if found, or null when no valid directory is found
     */
    protected static function searchLocaleDirectory(): ?string
    {
        if (static::$pearDirectory !== '@data_dir@') {
            $directory = static::$pearDirectory . '/' . static::$domain . '/locale';
            if (is_dir($directory)) {
                return $directory;
            }
        }

        $directories = static::getSearchDirectories();
        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                return $directory;
            }
        }

        return null;
    }

    /**
     * Get potential locations for the locale directory.
     *
     * @return string[] List of directories
     */
    protected static function getSearchDirectories(): array
    {
        $className = get_called_class();
        $class = new ReflectionClass($className);
        // @phpstan-ignore-next-line
        $basedir = dirname($class->getFilename());
        $depth = substr_count($className, '\\')
            ?: substr_count($className, '_');

        return array(
            /* Modern Composer PSR-4 case*/
            dirname($basedir) . '/locale',
            /* Traditional Composer */
            $basedir . str_repeat('/..', $depth) . '/data/locale',
            /* Source */
            $basedir . str_repeat('/..', $depth + 1) . '/locale'
        );
    }

}
