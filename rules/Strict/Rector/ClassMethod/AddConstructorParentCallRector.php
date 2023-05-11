<?php

declare(strict_types=1);

namespace Rector\Strict\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\NodeManipulator\Dependency\DependencyClassMethodDecorator;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\MethodName;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Fixer Rector for PHPStan rule:
 * https://github.com/phpstan/phpstan-strict-rules/blob/b7dd96a5503919a43b3cd06a2dced9d4252492f2/src/Rules/Classes/RequireParentConstructCallRule.php
 *
 * @see \Rector\Tests\Strict\Rector\ClassMethod\AddConstructorParentCallRector\AddConstructorParentCallRectorTest
 */
final class AddConstructorParentCallRector extends AbstractScopeAwareRector
{
    public function __construct(
        private readonly DependencyClassMethodDecorator $dependencyClassMethodDecorator,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        $errorMessage = sprintf(
            'Fixer for PHPStan reports by strict type rule - "%s"',
            'PHPStan\Rules\Classes\RequireParentConstructCallRule'
        );
        return new RuleDefinition(
            $errorMessage,
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SunshineCommand extends ParentClassWithConstructor
{
    public function __construct()
    {
        $value = 5;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SunshineCommand extends ParentClassWithConstructor
{
    public function __construct(ParentDependency $parentDependency)
    {
        $value = 5;

        parent::__construct($parentDependency);
    }
}
CODE_SAMPLE
                ),
            ]
        );
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
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if (! $this->isName($node, MethodName::CONSTRUCT)) {
            return null;
        }

        $classLike = $this->betterNodeFinder->findParentType($node, ClassLike::class);
        if (! $classLike instanceof Class_) {
            return null;
        }

        if ($this->hasParentCallOfMethod($node)) {
            return null;
        }

        $this->dependencyClassMethodDecorator->decorateConstructorWithParentDependencies($classLike, $node, $scope);

        return $node;
    }

    /**
     * Looks for "parent::__construct"
     */
    private function hasParentCallOfMethod(ClassMethod $classMethod): bool
    {
        return (bool) $this->betterNodeFinder->findFirst((array) $classMethod->stmts, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            if (! $this->isName($node->class, ObjectReference::PARENT)) {
                return false;
            }

            return $this->isName($node->name, MethodName::CONSTRUCT);
        });
    }
}
