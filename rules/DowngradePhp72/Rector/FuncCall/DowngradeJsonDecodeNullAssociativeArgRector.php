<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\NodeAnalyzer\ArgsAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp72\Rector\FuncCall\DowngradeJsonDecodeNullAssociativeArgRector\DowngradeJsonDecodeNullAssociativeArgRectorTest
 */
final class DowngradeJsonDecodeNullAssociativeArgRector extends AbstractRector
{
    public function __construct(private readonly ArgsAnalyzer $argsAnalyzer)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Downgrade json_decode() with null associative argument function', [
            new CodeSample(
                <<<'CODE_SAMPLE'
declare(strict_types=1);

$value = json_decode($json, null);
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
declare(strict_types=1);

$value = json_decode($json, false);
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
        if (! $this->isName($node, 'json_decode')) {
            return null;
        }

        $args = $node->getArgs();
        if ($this->argsAnalyzer->hasNamedArg($args)) {
            return null;
        }

        if (! isset($args[1])) {
            return null;
        }

        $associativeValue = $args[1]->value;

        if ($associativeValue instanceof ConstFetch && $this->valueResolver->isNull($associativeValue)) {
            $node->args[1]->value = $this->nodeFactory->createFalse();
            return $node;
        }

        return $node;
    }
}
