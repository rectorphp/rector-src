<?php

declare(strict_types=1);

namespace Rector\Php54\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php54\Rector\FuncCall\RemoveReferenceFromCallRector\RemoveReferenceFromCallRectorTest
 */
final class RemoveReferenceFromCallRector extends AbstractRector implements MinPhpVersionInterface
{
    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NO_REFERENCE_IN_ARG;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove & from function and method calls', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($one)
    {
        return strlen(&$one);
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($one)
    {
        return strlen($one);
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
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): FuncCall
    {
        foreach ($node->args as $nodeArg) {
            if ($nodeArg->byRef) {
                $nodeArg->byRef = false;
            }
        }

        return $node;
    }
}
