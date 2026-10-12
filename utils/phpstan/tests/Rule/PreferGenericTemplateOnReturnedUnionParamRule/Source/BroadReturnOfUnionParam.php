<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\PreferGenericTemplateOnReturnedUnionParamRule\Source;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;

final class BroadReturnOfUnionParam
{
    public function process(New_|MethodCall $node): ?Node
    {
        if ($node instanceof MethodCall) {
            return null;
        }

        return $node;
    }
}
