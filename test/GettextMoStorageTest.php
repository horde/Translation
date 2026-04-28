<?php

declare(strict_types=1);

namespace Horde\Translation\Test;

use Horde\Translation\GettextMoStorage;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(GettextMoStorage::class)]
class GettextMoStorageTest extends TestBase
{
    private GettextMoStorage $storage;

    public function setUp(): void
    {
        parent::setUp();
        $this->storage = new GettextMoStorage(
            'Horde_Translation',
            __DIR__ . '/fixtures/locale'
        );
    }

    public function testGetReturnsTranslation(): void
    {
        $this->assertSame('Heute', $this->storage->get('Today'));
    }

    public function testGetReturnsNullForUnknownKey(): void
    {
        $this->assertNull($this->storage->get('This key does not exist'));
    }

    public function testGetPluralSingular(): void
    {
        $result = $this->storage->getPlural('%d week', '%d weeks', 1);
        $this->assertSame('%d Woche', $result);
    }

    public function testGetPluralPlural(): void
    {
        $result = $this->storage->getPlural('%d week', '%d weeks', 2);
        $this->assertSame('%d Wochen', $result);
    }

    public function testGetPluralReturnsNullForUnknownKey(): void
    {
        $this->assertNull($this->storage->getPlural('unknown', 'unknowns', 1));
    }

    public function testInvalidPathThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new GettextMoStorage('Horde_Translation', __DIR__ . '/DOES_NOT_EXIST');
    }
}
