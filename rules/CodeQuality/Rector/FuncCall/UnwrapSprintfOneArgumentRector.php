<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/FriendsOfPHP/PHP-CS-Fixer/pull/5280
 * @see \Rector\Tests\CodeQuality\Rector\FuncCall\UnwrapSprintfOneArgumentRector\UnwrapSprintfOneArgumentRectorTest
 */
final class UnwrapSprintfOneArgumentRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('unwrap sprintf() with one argument', [
            new CodeSample(
                <<<'CODE_SAMPLE'
echo sprintf('value');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
echo 'value';
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'sprintf')) {
            return null;
        }

        if (count($node->args) > 1) {
            return null;
        }

        if ($node->getArgs()[0]->unpack) {
            return null;
        }

        return $node->getArgs()[0]->value;
    }
}
