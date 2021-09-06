<?php

declare(strict_types=1);

namespace Rector\Arguments\NodeAnalyzer;

use PhpParser\Node\Param;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;

class ChangedArgumentsDetector
{
    public function __construct(
        private ValueResolver $valueResolver,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function isDefaultValueChanged(Param $param, $value): bool
    {
        if ($param->default === null) {
            return false;
        }
        return ! $this->valueResolver->isValue($param->default, $value);
    }

    public function isTypeChanged(Param $param, ?string $tyoe): bool
    {
        if ($param->type === null) {
            return false;
        }
        if ($tyoe === null) {
            return true;
        }
        return ! $this->nodeNameResolver->isName($param->type, $tyoe);
    }
}
