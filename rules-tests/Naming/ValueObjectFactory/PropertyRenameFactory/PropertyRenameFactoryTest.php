<?php

declare(strict_types=1);

namespace Rector\Tests\Naming\ValueObjectFactory\PropertyRenameFactory;

use Iterator;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\FileSystemRector\Parser\FileInfoParser;
use Rector\Naming\ExpectedNameResolver\MatchPropertyTypeExpectedNameResolver;
use Rector\Naming\ValueObject\PropertyRename;
use Rector\Naming\ValueObjectFactory\PropertyRenameFactory;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class PropertyRenameFactoryTest extends AbstractTestCase
{
    private PropertyRenameFactory $propertyRenameFactory;

    private FileInfoParser $fileInfoParser;

    private BetterNodeFinder $betterNodeFinder;

    private MatchPropertyTypeExpectedNameResolver $matchPropertyTypeExpectedNameResolver;

    protected function setUp(): void
    {
        $this->boot();

        $this->propertyRenameFactory = $this->getService(PropertyRenameFactory::class);
        $this->matchPropertyTypeExpectedNameResolver = $this->getService(
            MatchPropertyTypeExpectedNameResolver::class
        );

        $this->fileInfoParser = $this->getService(FileInfoParser::class);
        $this->betterNodeFinder = $this->getService(BetterNodeFinder::class);
    }

    #[DataProvider('provideData')]
    public function test(string $filePathWithProperty, string $expectedName, string $currentName): void
    {
        $nodes = $this->fileInfoParser->parseFileInfoToNodesAndDecorate($filePathWithProperty);

        /** @var Class_ $class */
        $class = $this->betterNodeFinder->findFirstInstanceOf($nodes, Class_::class);

        $property = $class->getProperty($currentName);
        $this->assertInstanceOf(Property::class, $property);

        $expectedPropertyName = $this->matchPropertyTypeExpectedNameResolver->resolve($property, $class);
        if ($expectedPropertyName === null) {
            return;
        }

        $actualPropertyRename = $this->propertyRenameFactory->createFromExpectedName($property, $expectedPropertyName);
        $this->assertNotNull($actualPropertyRename);

        /** @var PropertyRename $actualPropertyRename */
        $this->assertSame($property, $actualPropertyRename->getProperty());
        $this->assertSame($expectedName, $actualPropertyRename->getExpectedName());
        $this->assertSame($currentName, $actualPropertyRename->getCurrentName());
    }

    public static function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/rename_property_and_property_fetch2.php.inc', 'eliteManager', 'eventManager'];
    }
}
