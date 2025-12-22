<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

use PhpParser\NodeTraverser;
use Rector\Comments\NodeVisitor\CommentRemovingNodeVisitor;

final class SkipDifferentType extends NodeTraverser
{
    public function __construct(CommentRemovingNodeVisitor $commentRemovingNodeVisitor)
    {
        parent::__construct($commentRemovingNodeVisitor);
    }
}
