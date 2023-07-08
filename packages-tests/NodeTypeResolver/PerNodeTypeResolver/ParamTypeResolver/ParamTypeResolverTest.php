<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\ParamTypeResolver;

use Iterator;
use PhpParser\Node\Param;
use PHPStan\Type\MixedType;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\AbstractNodeTypeResolverTestCase;

/**
 * @see \Rector\NodeTypeResolver\NodeTypeResolver\ParamTypeResolver
 */
final class ParamTypeResolverTest extends AbstractNodeTypeResolverTestCase
{
    #[DataProvider('provideData')]
    public function test(string $file, int $nodePosition, string $expectedType): void
    {
        $variableNodes = $this->getNodesForFileOfType($file, Param::class);

        $resolvedType = $this->nodeTypeResolver->getType($variableNodes[$nodePosition]);
        $this->assertSame($resolvedType::class, $expectedType);
    }

    public static function provideData(): Iterator
    {
        yield [__DIR__ . '/Source/MethodParamTypeHint.php', 0, FullyQualifiedObjectType::class];
        yield [__DIR__ . '/Source/MethodParamDocBlock.php', 0, MixedType::class];
    }
}
