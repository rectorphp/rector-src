<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeTraverser;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use Rector\PHPStan\NodeVisitor\ReIndexNodeAttributeVisitor;

final class ReIndexNodeAttributesTraverser extends NodeTraverser
{
    public function __construct(NodeVisitor ...$visitors)
    {
        parent::__construct(new ReIndexNodeAttributeVisitor());
    }
}
