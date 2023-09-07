<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\NewTypeResolver;

use Iterator;
use PhpParser\Node\Expr\New_;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\AbstractNodeTypeResolverTestCase;

/**
 * @see \Rector\NodeTypeResolver\NodeTypeResolver\NewTypeResolver
 */
final class NewTypeResolverTest extends AbstractNodeTypeResolverTestCase
{
    #[DataProvider('provideData')]
    public function test(string $file, int $nodePosition, Type $expectedType): void
    {
        $newNodes = $this->getNodesForFileOfType($file, New_::class);

        $resolvedType = $this->nodeTypeResolver->getType($newNodes[$nodePosition]);
        $this->assertEquals($expectedType, $resolvedType);

        $this->assertFalse(
            $this->nodeTypeResolver->isObjectType(
                $newNodes[$nodePosition],
                new ObjectType('Symfony\Bundle\TwigBundle\Loader\FilesystemLoader')
            )
        );
    }

    /**
     * @return Iterator<int[]|string[]|ObjectType[]>
     */
    public static function provideData(): Iterator
    {
        $objectWithoutClassType = new ObjectWithoutClassType();

        # test new
        yield [__DIR__ . '/Source/NewDynamicVariable.php', 0, $objectWithoutClassType];
    }
}
