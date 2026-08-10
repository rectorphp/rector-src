<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Rector\Configuration\RectorConfigBuilder;
use Rector\Set\Enum\SetGroup;
use ReflectionClass;

final class ExtensionComposerBasedSetTest extends TestCase
{
    /**
     * Both toggles of withComposerBased() that point at an extension package must have a set list mapped,
     * otherwise the lookup hits an undefined array key.
     */
    public function testEverySetGroupToggleHasASetListMapped(): void
    {
        $extensionSetLists = $this->provideExtensionComposerBasedSetLists();

        self::assertArrayHasKey(SetGroup::LARAVEL, $extensionSetLists);
        self::assertArrayHasKey(SetGroup::DRUPAL, $extensionSetLists);

        foreach ($extensionSetLists as $setListConstant) {
            self::assertMatchesRegularExpression('#^\w+(\\\\\w+)+::\w+$#', $setListConstant);
        }
    }

    /**
     * The extension packages are not required by rector-src, so their constant is undefined here and the
     * deprecated set group has to keep working.
     */
    public function testFallsBackToTheSetGroupWhenTheExtensionIsNotInstalled(): void
    {
        foreach ($this->provideExtensionComposerBasedSetLists() as $setListConstant) {
            self::assertFalse(defined($setListConstant), $setListConstant);
        }

        $rectorConfigBuilder = new RectorConfigBuilder()
            ->withComposerBased(laravel: true, drupal: true);

        self::assertSame([SetGroup::LARAVEL, SetGroup::DRUPAL], $this->readPrivateArray($rectorConfigBuilder, 'setGroups'));
        self::assertSame([], $this->readPrivateArray($rectorConfigBuilder, 'sets'));
    }

    /**
     * @return array<string, string>
     */
    private function provideExtensionComposerBasedSetLists(): array
    {
        $extensionSetLists = new ReflectionClass(RectorConfigBuilder::class)
            ->getConstant('EXTENSION_COMPOSER_BASED_SET_LISTS');

        self::assertIsArray($extensionSetLists);

        return $extensionSetLists;
    }

    /**
     * @return mixed[]
     */
    private function readPrivateArray(RectorConfigBuilder $rectorConfigBuilder, string $propertyName): array
    {
        $value = new ReflectionClass($rectorConfigBuilder)
            ->getProperty($propertyName)
            ->getValue($rectorConfigBuilder);

        self::assertIsArray($value);

        return $value;
    }
}
