<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PHPStan\Analyser\Scope;
use Rector\FamilyTree\NodeAnalyzer\ClassChildAnalyzer;
use PHPStan\Reflection\ClassReflection;

/**
 * @see \Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\AddParamBasedOnParentClassMethodRectorTest
 */
final class AddParamBasedOnParentClassMethodRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(private readonly ClassChildAnalyzer $classChildAnalyzer)
    {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::FATAL_ERROR_ON_INCOMPATIBLE_METHOD_SIGNATURE;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing parameter based on parent class method', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class A
{
    public function execute($foo)
    {
    }
}

class B extends A{
    public function execute()
    {
    }
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
class A
{
    public function execute($foo)
    {
    }
}

class B extends A{
    public function execute($foo)
    {
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        return $node;
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        $scope = $classMethod->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return true;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return true;
        }

        $methodName = (string) $this->nodeNameResolver->getName($classMethod);
        return $this->classChildAnalyzer->hasParentClassMethod($classReflection, $methodName);
    }
}
