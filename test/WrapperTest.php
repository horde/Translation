<?php
/**
 * @author     Jan Schneider <jan@horde.org>
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @category   Horde
 * @package    Translation
 * @subpackage UnitTests
 */
namespace Horde\Translation\Test;
use Horde_Translation;

class WrapperTest extends TestBase
{
    public function testWrappers()
    {
        $this->assertEquals('Heute', Helper\TestWrapperA::t('Today'));
        $this->assertEquals('Today', Helper\TestWrapperA::r('Today'));
        $this->assertEquals('1 Woche', sprintf(Helper\TestWrapperA::ngettext('%d week', '%d weeks', 1), 1));

        $this->assertEquals('Morgen', Helper\TestWrapperB::t('Tomorrow'));
        $this->assertEquals('Tomorrow', Helper\TestWrapperB::r('Tomorrow'));
    }
}
