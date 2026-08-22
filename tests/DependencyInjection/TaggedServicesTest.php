<?php

declare(strict_types=1);

namespace Rector\Tests\DependencyInjection;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\BetterPhpDocParser\Contract\BasePhpDocNodeVisitorInterface;
use Rector\Contract\DependencyInjection\ResettableInterface;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class TaggedServicesTest extends AbstractLazyTestCase
{
    /**
     * Registering the same class under the same tag twice makes every consumer of that tag
     * run it twice, e.g. each phpdoc node visitor traversing every doc node twice
     *
     * @param class-string $tagInterface
     */
    #[DataProvider('provideTagInterfaces')]
    public function testServiceIsTaggedOnce(string $tagInterface): void
    {
        $taggedServices = self::getContainer()->findByContract($tagInterface);

        $classNames = array_map(static fn (object $service): string => $service::class, $taggedServices);

        $this->assertSame(array_values(array_unique($classNames)), $classNames);
    }

    /**
     * @return Iterator<array{class-string}>
     */
    public static function provideTagInterfaces(): Iterator
    {
        yield [BasePhpDocNodeVisitorInterface::class];
        yield [ResettableInterface::class];
    }
}
