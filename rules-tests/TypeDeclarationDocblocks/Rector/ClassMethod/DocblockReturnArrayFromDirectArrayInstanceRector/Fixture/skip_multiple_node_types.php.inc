<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SkipMultipleObjectTypes extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [Class_::class, ClassMethod::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function refactor(Node $node)
    {
    }
}
