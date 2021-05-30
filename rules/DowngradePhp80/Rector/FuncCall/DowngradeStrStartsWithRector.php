<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/add_str_starts_with_and_ends_with_functions
 *
 * @see \Rector\Tests\DowngradePhp80\Rector\FuncCall\DowngradeStrStartsWithRector\DowngradeStrStartsWithRectorTest
 */
final class DowngradeStrStartsWithRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Downgrade str_starts_with() to strncmp() version', [
            new CodeSample(
                'str_starts_with($haystack, $needle);',
                'strncmp($haystack, $needle, strlen($needle)) === 0;'
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class, BooleanNot::class];
    }

    /**
     * @param FuncCall|BooleanNot $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof FuncCall && $this->isName($node, 'str_starts_with')) {
            return $this->createStrncmpFuncCall($node);
        }

        return null;
    }

    private function createStrncmpFuncCall(FuncCall $funcCall): Identical
    {
        $newArgs = $funcCall->args;
        $strlenFuncCall = new FuncCall(new Name('strlen'), [$funcCall->args[1]]);

        $newArgs[] = new Arg($strlenFuncCall);
        $strncmpFuncCall = new FuncCall(new Name('strncmp'), $newArgs);

        return new Identical($strncmpFuncCall, new LNumber(0));
    }
}
