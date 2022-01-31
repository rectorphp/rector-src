<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\Rector\AbstractRector;
use Rector\FamilyTree\NodeAnalyzer\ClassChildAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PHPStan\Type\MixedType;

/**
 * @see \Rector\Tests\DowngradePhp80\Rector\ClassMethod\DowngradeStringReturnTypeOnToStringRector\DowngradeStringReturnTypeOnToStringRectorTest
 */
final class DowngradeStringReturnTypeOnToStringRector extends AbstractRector
{
    public function __construct(
        private readonly ClassChildAnalyzer $classChildAnalyzer
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
            'Add "string" return on child when parent has string return on __toString() method',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
abstract class ParentClass
{
    public function __toString(): string
    {
        return 'value';
    }
}

class ChildClass extends ParentClass
{
    public function __toString()
    {
        return 'value';
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
abstract class ParentClass
{
    public function __toString(): string
    {
        return 'value';
    }
}

class ChildClass extends ParentClass
{
    public function __toString(): string
    {
        return 'value';
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $node->returnType = new Name('string');
        return $node;
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        if (! $this->nodeNameResolver->isName($classMethod, '__toString')) {
            return true;
        }

        if ($classMethod->returnType instanceof Node) {
            return true;
        }

        $scope = $classMethod->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return true;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return true;
        }

        $type = $this->classChildAnalyzer->resolveParentClassMethodReturnType($classReflection, '__toString');
        return $type instanceof MixedType;
    }
}
