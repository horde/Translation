<?php

declare(strict_types=1);

namespace Horde\Translation\Test;

use Horde\Translation\NullHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NullHandler::class)]
class NullHandlerTest extends TestCase
{
    private NullHandler $handler;

    public function setUp(): void
    {
        $this->handler = new NullHandler();
    }

    public function testTReturnsInput(): void
    {
        $this->assertSame('Hello', $this->handler->t('Hello'));
        $this->assertSame('', $this->handler->t(''));
    }

    public function testNgettextReturnsSingular(): void
    {
        $this->assertSame('1 item', $this->handler->ngettext('1 item', '2 items', 1));
    }

    public function testNgettextReturnsPlural(): void
    {
        $this->assertSame('2 items', $this->handler->ngettext('1 item', '2 items', 2));
        $this->assertSame('2 items', $this->handler->ngettext('1 item', '2 items', 0));
    }

    public function testFormatReturnsInput(): void
    {
        $message = '{count, plural, one {# item} other {# items}}';
        $this->assertSame($message, $this->handler->format($message, ['count' => 5], 'de'));
        $this->assertSame($message, $this->handler->format($message));
    }
}
