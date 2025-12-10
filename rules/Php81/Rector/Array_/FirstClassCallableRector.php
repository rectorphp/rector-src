<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\Array_;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Configuration\Deprecation\Contract\DeprecatedInterface;
use Rector\Exception\ShouldNotHappenException;

/**
 * @deprecated Renamed to \Rector\Php81\Rector\Array_\ArrayToFirstClassCallableRector
 */
final class FirstClassCallableRector extends ArrayToFirstClassCallableRector implements DeprecatedInterface
{
    public function refactor(Node $node): StaticCall|MethodCall|null
    {
        throw new ShouldNotHappenException(sprintf(
            '%s is deprecated and renamed to "%s". Use it instead.',
            self::class,
            ArrayToFirstClassCallableRector::class
        ));
    }
}
