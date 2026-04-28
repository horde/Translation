<?php

declare(strict_types=1);

namespace Horde\Translation\Test;

use Horde\Translation\IcuHandler;
use Horde\Translation\JsonStorage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use MessageFormatter;

#[CoversClass(IcuHandler::class)]
#[UsesClass(JsonStorage::class)]
class IcuHandlerTest extends TestBase
{
    private IcuHandler $handler;

    public function setUp(): void
    {
        parent::setUp();

        if (!class_exists(MessageFormatter::class)) {
            $this->markTestSkipped('ext-intl required for IcuHandler tests');
        }

        $this->handler = new IcuHandler(
            'Horde_Translation',
            __DIR__ . '/fixtures/locale'
        );
    }

    public function testTIsNoop(): void
    {
        $this->assertSame('Today', $this->handler->t('Today'));
    }

    public function testNgettextIsNoop(): void
    {
        $this->assertSame('%d week', $this->handler->ngettext('%d week', '%d weeks', 1));
        $this->assertSame('%d weeks', $this->handler->ngettext('%d week', '%d weeks', 2));
    }

    public function testFormatPlural(): void
    {
        $result = $this->handler->format(
            '{count, plural, one {# week} other {# weeks}}',
            ['count' => 2],
            'de'
        );
        $this->assertSame('2 Wochen', $result);
    }

    public function testFormatPluralSingular(): void
    {
        $result = $this->handler->format(
            '{count, plural, one {# week} other {# weeks}}',
            ['count' => 1],
            'de'
        );
        $this->assertSame('1 Woche', $result);
    }

    public function testFormatPluralDays(): void
    {
        $result = $this->handler->format(
            '{count, plural, one {# day} other {# days}}',
            ['count' => 3],
            'de'
        );
        $this->assertSame('3 Tage', $result);
    }

    public function testFormatReturnsInputWhenKeyNotFound(): void
    {
        $message = '{count, plural, one {# thing} other {# things}}';
        $result = $this->handler->format($message, ['count' => 1], 'de');
        $this->assertSame($message, $result);
    }

    public function testFormatWithExplicitLocale(): void
    {
        $result = $this->handler->format(
            '{count, plural, one {# day} other {# days}}',
            ['count' => 1],
            'de_DE'
        );
        $this->assertSame('1 Tag', $result);
    }
}
