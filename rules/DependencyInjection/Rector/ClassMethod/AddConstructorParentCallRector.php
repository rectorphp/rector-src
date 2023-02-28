<?php

declare(strict_types=1);

namespace Rector\DependencyInjection\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\NodeManipulator\Dependency\DependencyClassMethodDecorator;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DependencyInjection\Rector\ClassMethod\AddConstructorParentCallRector\AddConstructorParentCallRectorTest
 */
final class AddConstructorParentCallRector extends AbstractRector
{
    public function __construct(
        private readonly DependencyClassMethodDecorator $dependencyClassMethodDecorator,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add constructor parent call',
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
    public function refactor(Node $node): ?Node
    {
        $class = $this->betterNodeFinder->findParentType($node, ClassLike::class);
        if (! $class instanceof Class_) {
            return null;
        }

        if (! $this->isName($node, MethodName::CONSTRUCT)) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return null;
        }

        if ($this->hasParentCallOfMethod($node)) {
            return null;
        }

        $this->dependencyClassMethodDecorator->decorateConstructorWithParentDependencies($class, $node, $scope);

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
