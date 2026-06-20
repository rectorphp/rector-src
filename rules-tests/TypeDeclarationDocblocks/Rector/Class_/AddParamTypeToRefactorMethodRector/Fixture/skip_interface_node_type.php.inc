<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddParamTypeToRefactorMethodRector\Fixture;

use PhpParser\Node;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SkipInterfaceNodeType extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function getNodeTypes(): array
    {
        return [StmtsAwareInterface::class];
    }

    public function refactor(Node $node): ?Node
    {
    }
}
