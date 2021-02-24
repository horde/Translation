<?php
/**
 * @author     Jan Schneider <jan@horde.org>
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @category   Horde
 * @package    Translation
 * @subpackage UnitTests
 */
namespace Horde\Translation\Test;
use \PHPUnit\Framework\TestCase;
use \PHPUnit\Framework\Exception as PHPUnitException;

class TestBase extends TestCase
{
    private $_env;

    public function setUp(): void
    {
        try {
            $this->setLocale(LC_ALL, 'de_DE.UTF-8');
        } catch (PHPUnitException $e) {
            $this->markTestSkipped('Setting the locale failed. de_DE.UTF-8 might not be supported.');
        }
        $this->_setEnv('de_DE.UTF-8');
    }

    public function tearDown(): void
    {
        $this->_restoreEnv();
    }

    private function _setEnv($value)
    {
        foreach (array('LC_ALL', 'LANG', 'LANGUAGE') as $env) {
            $this->_env[$env] = getenv($env);
            putenv($env . '=' . $value);
        }
    }

    private function _restoreEnv()
    {
        foreach (array('LC_ALL', 'LANG', 'LANGUAGE') as $env) {
            putenv($env . '=' . $this->_env[$env]);
        }
    }
}
