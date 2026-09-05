<?php

declare(strict_types=1);

namespace Rector\Simple\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Rector\SimpleScope\SimpleScope;
use Rector\SimpleScope\SimpleScopeResolver;
use Rector\SimpleType\ObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

// POC: type resolution done via SimpleScope, no PHPStan
/**
 * @see \Rector\Tests\Simple\Rector\ClassMethod\DemoObjectMethodCallRenameRector\DemoObjectMethodCallRenameRectorTest
 */
final class DemoObjectMethodCallRenameRector extends AbstractRector
{
    private const string TARGET_CLASS = 'DateTime';

    private const string OLD_METHOD = 'oldMethod';

    private const string NEW_METHOD = 'newMethod';

    public function __construct(
        private readonly SimpleScopeResolver $simpleScopeResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Demo of SimpleScope: rename oldMethod() to newMethod() on DateTime calls, type resolved without PHPStan',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$dateTime = new DateTime();
$dateTime->oldMethod();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$dateTime = new DateTime();
$dateTime->newMethod();
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $stmts = $node->stmts;
        if ($stmts === null) {
            return null;
        }

        $simpleScope = $this->simpleScopeResolver->resolve([$node]);

        $hasChanged = false;
        $this->traverseNodesWithCallable($stmts, function (Node $subNode) use ($simpleScope, &$hasChanged): null {
            if ($this->refactorMethodCall($subNode, $simpleScope)) {
                $hasChanged = true;
            }

            return null;
        });

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    private function refactorMethodCall(Node $node, SimpleScope $simpleScope): bool
    {
        if (! $node instanceof MethodCall) {
            return false;
        }

        if ($node->isFirstClassCallable()) {
            return false;
        }

        if (! $this->isName($node->name, self::OLD_METHOD)) {
            return false;
        }

        $simpleType = $simpleScope->getType($node->var);
        if (! $simpleType instanceof ObjectType) {
            return false;
        }

        if ($simpleType->getClassName() !== self::TARGET_CLASS) {
            return false;
        }

        $node->name = new Identifier(self::NEW_METHOD);

        return true;
    }
}
