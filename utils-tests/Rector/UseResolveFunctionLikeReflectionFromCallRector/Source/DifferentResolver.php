<?php

declare(strict_types=1);

namespace Rector\Utils\Tests\Rector\UseResolveFunctionLikeReflectionFromCallRector\Source;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;

final class DifferentResolver
{
    public function resolveMethodReflectionFromMethodCall(MethodCall $methodCall): mixed
    {
        return null;
    }

    public function resolveMethodReflectionFromStaticCall(StaticCall $staticCall): mixed
    {
        return null;
    }
}