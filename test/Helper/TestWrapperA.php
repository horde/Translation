<?php
/**
 * @author     Jan Schneider <jan@horde.org>
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @category   Horde
 * @package    Translation
 * @subpackage UnitTests
 */
namespace Horde\Translation\Test\Helper;
use \Horde_Translation;

class TestWrapperA extends Horde_Translation
{
    public static function t($message)
    {
        self::$_domain = 'Horde_Translation';
        self::$_directory = __DIR__ . '/../fixtures/locale';
        return parent::t($message);
    }

    public static function ngettext($singular, $plural, $number)
    {
        self::$_domain = 'Horde_Translation';
        self::$_directory = __DIR__ . '/../fixtures/locale';
        return parent::ngettext($singular, $plural, $number);
    }
}
