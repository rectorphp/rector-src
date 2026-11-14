<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\NewTypeResolver;

use Iterator;
use PhpParser\Node\Expr\New_;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\NodeTypeResolver\PHPStan\ObjectWithoutClassTypeWithParentTypes;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\AbstractNodeTypeResolverTestCase;
use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\NewTypeResolver\Source\SomeRealType;

/**
 * @see \Rector\NodeTypeResolver\NodeTypeResolver\NewTypeResolver
 */
final class NewTypeResolverTest extends AbstractNodeTypeResolverTestCase
{
    #[DataProvider('provideData')]
    public function test(string $file, int $nodePosition, Type $expectedType, bool $isObjectType): void
    {
        $newNodes = $this->getNodesForFileOfType($file, New_::class);

        $resolvedType = $this->nodeTypeResolver->getType($newNodes[$nodePosition]);
        $this->assertEquals($expectedType, $resolvedType);

        $this->assertSame(
            $isObjectType,
            $this->nodeTypeResolver->isObjectType(
                $newNodes[$nodePosition],
                new ObjectType(SomeRealType::class)
            )
        );
    }

    /**
     * @return Iterator<int[]|string[]|ObjectWithoutClassType[]|ObjectWithoutClassTypeWithParentTypes[]|bool[]>
     */
    public static function provideData(): Iterator
    {
        $objectWithoutClassType = new ObjectWithoutClassType();

        # test new
        yield [__DIR__ . '/Source/NewDynamicNew.php', 0, $objectWithoutClassType, false];

        $objectWithoutClassTypeWithParentTypes = new ObjectWithoutClassTypeWithParentTypes(
            [new FullyQualifiedObjectType(SomeRealType::class)]
        );
        yield [__DIR__ . '/Source/NewDynamicNewExtends.php', 0, $objectWithoutClassTypeWithParentTypes, true];
    }
}
