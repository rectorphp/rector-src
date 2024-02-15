<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Enum\ObjectReference;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://3v4l.org/JK1g2#v5.0.0
 * @see \Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\StaticToSelfStaticMethodCallOnFinalClassRectorTest
 */
final class StaticToSelfStaticMethodCallOnFinalClassRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change `static::methodCall()` to `self::methodCall()` on final class', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function d()
    {
        echo static::run();
    }

    private static function run()
    {
        echo 'test';
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function d()
    {
        echo self::run();
    }

    private static function run()
    {
        echo 'test';
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Class_
    {
        if (! $node->isFinal()) {
            return null;
        }

        $hasChanged = false;

        $this->traverseNodesWithCallable($node->stmts, function (Node $subNode) use (&$hasChanged, $node): ?StaticCall {
            if (! $subNode instanceof StaticCall) {
                return null;
            }

            if (! $this->isName($subNode->class, ObjectReference::STATIC)) {
                return null;
            }

            $methodName = $this->getName($subNode->name);

            // skip dynamic method
            if ($methodName === null) {
                return null;
            }

            // skip call non-existing method from current class to ensure transformation is safe
            if (! $node->getMethod($methodName) instanceof ClassMethod) {
                return null;
            }

            $hasChanged = true;
            return $this->nodeFactory->createSelfMethod($methodName);
        });

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}
