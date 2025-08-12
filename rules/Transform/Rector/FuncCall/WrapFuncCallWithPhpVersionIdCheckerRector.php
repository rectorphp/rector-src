<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\DeadCode\ValueObject\WrapFuncCallWithPhpVersionIdChecker;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Transform\Rector\FuncCall\WrapFuncCallWithPhpVersionIdCheckerRector\WrapFuncCallWithPhpVersionIdCheckerRectorTest
 */
final class WrapFuncCallWithPhpVersionIdCheckerRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var WrapFuncCallWithPhpVersionIdChecker[]
     */
    private array $wrapFuncCallWithPhpVersionIdCheckers = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Wraps function calls without assignment in a condition to check for a PHP version id',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
    no_op_function();
    CODE_SAMPLE

                    ,
                    <<<'CODE_SAMPLE'
    if (PHP_VERSION_ID < 80500) {
        no_op_function();
    }
    CODE_SAMPLE
                    ,
                    [new WrapFuncCallWithPhpVersionIdChecker('no_op_function', 80500)]
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->expr instanceof FuncCall) {
            return null;
        }

        $funcCall = $node->expr;

        foreach ($this->wrapFuncCallWithPhpVersionIdCheckers as $wrapFuncCallWithPhpVersionIdChecker) {
            if ($this->getName($funcCall) !== $wrapFuncCallWithPhpVersionIdChecker->getFunctionName()) {
                continue;
            }

            $phpVersionIdConst = new ConstFetch(new Name('PHP_VERSION_ID'));
            $if = new If_(new Smaller($phpVersionIdConst, new Int_(
                $wrapFuncCallWithPhpVersionIdChecker->getPhpVersionId()
            )));
            $if->stmts = [$node];

            return $if;
        }

        return null;
    }

    public function configure(array $configuration): void
    {
        Assert::allIsInstanceOf($configuration, WrapFuncCallWithPhpVersionIdChecker::class);

        $this->wrapFuncCallWithPhpVersionIdCheckers = $configuration;
    }
}
