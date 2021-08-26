<?php

declare(strict_types=1);

namespace Rector\DowngradePhp70\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocParser\PhpDocFromTypeDeclarationDecorator;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\ValueObject\Type\ParentStaticType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp70\Rector\ClassMethod\DowngradeParentTypeDeclarationRector\DowngradeParentTypeDeclarationRectorTest
 */
final class DowngradeParentTypeDeclarationRector extends AbstractRector
{
    public function __construct(
        private PhpDocFromTypeDeclarationDecorator $phpDocFromTypeDeclarationDecorator,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove "parent" return type, add a "@return parent" tag instead',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class ParentClass
{
}

class SomeClass extends ParentClass
{
    public function foo(): parent
    {
        return $this;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class ParentClass
{
}

class SomeClass extends ParentClass
{
    /**
     * @return parent
     */
    public function foo()
    {
        return $this;
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if ($scope === null) {
            // in a trait
            $className = $node->getAttribute(AttributeKey::CLASS_NAME);
            $classReflection = $this->reflectionProvider->getClass($className);
        } else {
            $classReflection = $scope->getClassReflection();
        }

        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $parentStaticType = new ParentStaticType($classReflection);

        if (! $this->phpDocFromTypeDeclarationDecorator->decorateReturnWithSpecificType($node, $parentStaticType)) {
            return null;
        }

        return $node;
    }
}
