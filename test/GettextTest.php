<?php
/**
 * @author     Jan Schneider <jan@horde.org>
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @category   Horde
 * @package    Translation
 * @subpackage UnitTests
 */
namespace Horde\Translation\Test;
use Horde_Translation_Handler_Gettext;

class GettextTest extends TestBase
{
    private $_dict;
    private $_otherDict;

    public function setUp(): void
    {
        parent::setUp();
        $this->_dict = new Horde_Translation_Handler_Gettext('Horde_Translation', __DIR__ . '/fixtures/locale');
        $this->_otherDict = new Horde_Translation_Handler_Gettext('Horde_Other', __DIR__ . '/fixtures/locale');
    }

    public function testGettext()
    {
        $this->assertEquals('Heute', $this->_dict->t('Today'));
        $this->assertEquals('Schön', $this->_dict->t('Beautiful'));
        $this->assertEquals('2 Tage', sprintf($this->_dict->t('%d days'), 2));
        $this->assertEquals('Morgen', $this->_otherDict->t('Tomorrow'));
    }

    public function testNgettext()
    {
        $this->assertEquals('1 Woche', sprintf($this->_dict->ngettext('%d week', '%d weeks', 1), 1));
        $this->assertEquals('2 Wochen', sprintf($this->_dict->ngettext('%d week', '%d weeks', 2), 2));
    }

    public function testInvalidConstruction()
    {
        $this->expectException('InvalidArgumentException');
        new Horde_Translation_Handler_Gettext('Horde_Translation', __DIR__ . '/DOES_NOT_EXIST');
    }
}
