<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor;

use PhpParser\NodeVisitor;
use Rector\Contract\PhpParser\DecoratingNodeVisitorInterface;

/**
 * @deprecated Use
 * @see DecoratingNodeVisitorInterface instead
 */
interface ScopeResolverNodeVisitorInterface extends NodeVisitor
{
}
