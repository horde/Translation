<?php

declare(strict_types=1);

namespace Horde\Translation\Test;

use Horde\Translation\JsonStorage;
use PHPUnit\Framework\Attributes\CoversClass;
use MessageFormatter;

#[CoversClass(JsonStorage::class)]
class JsonStorageTest extends TestBase
{
    private JsonStorage $storage;

    public function setUp(): void
    {
        parent::setUp();
        $this->storage = new JsonStorage(
            'Horde_Translation',
            __DIR__ . '/fixtures/locale'
        );
    }

    public function testGetReturnsTranslation(): void
    {
        $this->assertSame('Heute', $this->storage->get('Today'));
    }

    public function testGetReturnsTranslationWithSpecialChars(): void
    {
        $this->assertSame('Schön', $this->storage->get('Beautiful'));
    }

    public function testGetReturnsNullForUnknownKey(): void
    {
        $this->assertNull($this->storage->get('This key does not exist'));
    }

    public function testGetReturnsNullForMissingCatalog(): void
    {
        $storage = new JsonStorage('NonExistent', __DIR__ . '/fixtures/locale');
        $this->assertNull($storage->get('Today'));
    }

    public function testGetReturnsNullForMissingPath(): void
    {
        $storage = new JsonStorage('Horde_Translation', __DIR__ . '/nonexistent');
        $this->assertNull($storage->get('Today'));
    }

    public function testGetPluralWithIcuPattern(): void
    {
        if (!class_exists(MessageFormatter::class)) {
            $this->markTestSkipped('ext-intl required');
        }

        $result = $this->storage->getPlural(
            '{count, plural, one {# week} other {# weeks}}',
            '{count, plural, one {# week} other {# weeks}}',
            2
        );
        $this->assertSame('2 Wochen', $result);
    }

    public function testGetPluralWithIcuPatternSingular(): void
    {
        if (!class_exists(MessageFormatter::class)) {
            $this->markTestSkipped('ext-intl required');
        }

        $result = $this->storage->getPlural(
            '{count, plural, one {# week} other {# weeks}}',
            '{count, plural, one {# week} other {# weeks}}',
            1
        );
        $this->assertSame('1 Woche', $result);
    }

    public function testGetPluralFallsBackToDirectLookup(): void
    {
        $result = $this->storage->getPlural('%d days', '%d days', 2);
        $this->assertSame('%d Tage', $result);
    }
}
