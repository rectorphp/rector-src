<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\VariableTypeResolver;

use Iterator;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeWithClassName;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\AbstractNodeTypeResolverTestCase;
use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\VariableTypeResolver\Source\AnotherType;

/**
 * @see \Rector\NodeTypeResolver\NodeTypeResolver\VariableTypeResolver
 */
final class VariableTypeResolverTest extends AbstractNodeTypeResolverTestCase
{
    #[DataProvider('provideData')]
    public function test(string $file, int $nodePosition, TypeWithClassName $expectedTypeWithClassName): void
    {
        $variableNodes = $this->getNodesForFileOfType($file, Variable::class);

        $resolvedType = $this->nodeTypeResolver->getType($variableNodes[$nodePosition]);
        $this->assertInstanceOf(TypeWithClassName::class, $resolvedType);

        /** @var TypeWithClassName $resolvedType */
        $this->assertSame($expectedTypeWithClassName->getClassName(), $resolvedType->getClassName());
    }

    public static function provideData(): Iterator
    {
        $anotherTypeObjectType = new ObjectType(AnotherType::class);
        yield [__DIR__ . '/Fixture/new_class.php.inc', 1, $anotherTypeObjectType];
        yield [__DIR__ . '/Fixture/new_class.php.inc', 3, $anotherTypeObjectType];
        yield [__DIR__ . '/Fixture/assignment_class.php.inc', 2, $anotherTypeObjectType];
        yield [__DIR__ . '/Fixture/argument_typehint.php.inc', 1, $anotherTypeObjectType];
    }
}
