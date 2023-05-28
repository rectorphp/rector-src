<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php80\NodeManipulator\TokenManipulator;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/token_as_object
 *
 * @see \Rector\Tests\Php80\Rector\FuncCall\TokenGetAllToObjectRector\TokenGetAllToObjectRectorTest
 */
final class TokenGetAllToObjectRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly TokenManipulator $tokenManipulator,
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::PHP_TOKEN;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert `token_get_all()` to `PhpToken::tokenize()`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $tokens = token_get_all($code);

        foreach ($tokens as $token) {
            if (is_array($token)) {
               $name = token_name($token[0]);
               $text = $token[1];
            } else {
               $name = null;
               $text = $token;
            }
        }
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $tokens = \PhpToken::tokenize($code);

        foreach ($tokens as $phpToken) {
            $name = $phpToken->getTokenName();
            $text = $phpToken->text;
        }
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
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $tokensVariable = null;

        $this->traverseNodesWithCallable($node, function (Node $node) use (&$tokensVariable) {
            if ($node instanceof Assign && $node->expr instanceof FuncCall) {
                $funcCall = $node->expr;
                if (! $this->nodeNameResolver->isName($funcCall, 'token_get_all')) {
                    return null;
                }

                $tokensVariable = $node->var;

                $node->expr = $this->nodeFactory->createStaticCall('PhpToken', 'tokenize', $funcCall->getArgs());

                return $node;
            }

            return null;
        });

        if (! $tokensVariable instanceof Variable) {
            return null;
        }

        $this->replaceGetNameOrGetValue($node, $tokensVariable);
        return $node;
    }

    private function replaceGetNameOrGetValue(ClassMethod | Function_ $functionLike, Expr $assignedExpr): void
    {
        $tokensForeaches = $this->findForeachesOverTokenVariable($functionLike, $assignedExpr);

        foreach ($tokensForeaches as $tokenForeach) {
            $this->refactorTokenInForeach($tokenForeach);
        }
    }

    /**
     * @return Foreach_[]
     */
    private function findForeachesOverTokenVariable(ClassMethod | Function_ $functionLike, Expr $assignedExpr): array
    {
        return $this->betterNodeFinder->find((array) $functionLike->stmts, function (Node $node) use (
            $assignedExpr
        ): bool {
            if (! $node instanceof Foreach_) {
                return false;
            }

            return $this->nodeComparator->areNodesEqual($node->expr, $assignedExpr);
        });
    }

    private function refactorTokenInForeach(Foreach_ $tokensForeach): void
    {
        $singleToken = $tokensForeach->valueVar;
        if (! $singleToken instanceof Variable) {
            return;
        }

        $this->traverseNodesWithCallable($tokensForeach, function (Node $node) use ($singleToken) {
            $this->tokenManipulator->refactorArrayToken([$node], $singleToken);
            $this->tokenManipulator->refactorNonArrayToken([$node], $singleToken);
            $this->tokenManipulator->refactorTokenIsKind([$node], $singleToken);
            $this->tokenManipulator->removeIsArray([$node], $singleToken);

            // drop if "If_" node not needed
            if ($node instanceof If_ && $node->else instanceof Else_) {
                if (! $this->nodeComparator->areNodesEqual($node->stmts, $node->else->stmts)) {
                    return null;
                }

                return $node->stmts;
            }
            return null;
        });
    }
}
