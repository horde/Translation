<?php

/**
 * @author     Jan Schneider <jan@horde.org>
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @category   Horde
 * @package    Translation
 * @subpackage UnitTests
 */

namespace Horde\Translation\Test;

use PHPUnit\Framework\TestCase;

/**
 * Base test class for Horde\Translation tests.
 */
class TestBase extends TestCase
{
    private $_env;

    public function setUp(): void
    {
        // Try to set locale
        $result = setlocale(LC_ALL, 'de_DE.UTF-8');
        if ($result === false) {
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
        foreach (['LC_ALL', 'LANG', 'LANGUAGE'] as $env) {
            $this->_env[$env] = getenv($env);
            putenv($env . '=' . $value);
        }
    }

    private function _restoreEnv()
    {
        foreach (['LC_ALL', 'LANG', 'LANGUAGE'] as $env) {
            putenv($env . '=' . $this->_env[$env]);
        }
    }
}
