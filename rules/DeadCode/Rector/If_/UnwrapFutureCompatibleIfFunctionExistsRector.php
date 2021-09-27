<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\If_;
use PHPStan\Type\ObjectType;
use Rector\Core\NodeManipulator\IfManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\DeadCode\FeatureSupport\FunctionSupportResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\If_\UnwrapFutureCompatibleIfFunctionExistsRector\UnwrapFutureCompatibleIfFunctionExistsRectorTest
 */
final class UnwrapFutureCompatibleIfFunctionExistsRector extends AbstractRector
{
    public function __construct(
        private FunctionSupportResolver $functionSupportResolver,
        private IfManipulator $ifManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove functions exists if with else for always existing',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        // session locking trough other addons
        if (function_exists('session_abort')) {
            session_abort();
        } else {
            session_write_close();
        }
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        // session locking trough other addons
        session_abort();
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
        return [If_::class];
    }

    /**
     * @param If_ $node
     * @return null|Stmt[]
     */
    public function refactor(Node $node): ?array
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $match = $this->ifManipulator->isIfOrIfElseWithFunctionCondition($node, 'function_exists');
        if (! $match) {
            return null;
        }

        /** @var FuncCall $funcCall */
        $funcCall = $node->cond;

        if (! isset($funcCall->args[0])) {
            return null;
        }

        if (! $funcCall->args[0] instanceof Arg) {
            return null;
        }

        $functionToExistName = $this->valueResolver->getValue($funcCall->args[0]->value);
        if (! is_string($functionToExistName)) {
            return null;
        }

        if (! $this->functionSupportResolver->isFunctionSupported($functionToExistName)) {
            return null;
        }

        return $node->stmts;
    }

    private function shouldSkip(If_ $if): bool
    {
        $classLike = $if->getAttribute(AttributeKey::CLASS_NODE);
        if (! $classLike instanceof ClassLike) {
            return false;
        }

        // skip rector rules, as they decided if function exists in that particular projects
        return $this->isObjectType($classLike, new ObjectType('Rector\Core\Contract\Rector\RectorInterface'));
    }
}
