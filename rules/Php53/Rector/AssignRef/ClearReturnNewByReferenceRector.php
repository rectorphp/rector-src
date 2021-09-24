<?php

declare(strict_types=1);

namespace Rector\Php53\Rector\AssignRef;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\New_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://3v4l.org/UJN6H
 * @see \Rector\Tests\Php53\Rector\AssignRef\ClearReturnNewByReferenceRector\ClearReturnNewByReferenceRectorTest
 */
final class ClearReturnNewByReferenceRector extends AbstractRector implements MinPhpVersionInterface
{
    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NO_REFERENCE_IN_NEW;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove reference from "$assign = &new Value;"',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$assign = &new Value;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$assign = new Value;
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
        return [AssignRef::class];
    }

    /**
     * @param AssignRef $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->expr instanceof New_) {
            return null;
        }

        return new Assign($node->var, $node->expr);
    }
}
