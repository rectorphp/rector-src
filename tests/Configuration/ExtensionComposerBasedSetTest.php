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

        $this->assertArrayHasKey(SetGroup::LARAVEL, $extensionSetLists);
        $this->assertArrayHasKey(SetGroup::DRUPAL, $extensionSetLists);

        foreach ($extensionSetLists as $extensionSetList) {
            $this->assertMatchesRegularExpression('#^\w+(\\\\\w+)+::\w+$#', $extensionSetList);
        }
    }

    /**
     * The extension packages are not required by rector-src, so their constant is undefined here and
     * nothing is loaded for the toggle.
     */
    public function testLoadsNothingWhenTheExtensionIsNotInstalled(): void
    {
        foreach ($this->provideExtensionComposerBasedSetLists() as $setListConstant) {
            $this->assertFalse(defined($setListConstant), $setListConstant);
        }

        $rectorConfigBuilder = new RectorConfigBuilder()
            ->withComposerBased(laravel: true, drupal: true);

        $this->assertSame([], $this->readPrivateArray($rectorConfigBuilder, 'sets'));
    }

    /**
     * @return array<string, string>
     */
    private function provideExtensionComposerBasedSetLists(): array
    {
        $extensionSetLists = new ReflectionClass(RectorConfigBuilder::class)
            ->getConstant('EXTENSION_COMPOSER_BASED_SET_LISTS');

        $this->assertIsArray($extensionSetLists);

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

        $this->assertIsArray($value);

        return $value;
    }
}
