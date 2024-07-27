<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;

final readonly class ScalarValueToConstFetch
{
    public function __construct(
        private DNumber|String_|LNumber $scalar,
        private ConstFetch|ClassConstFetch $constFetch
    ) {
    }

    public function getScalar(): DNumber|String_|LNumber
    {
        return $this->scalar;
    }

    public function getConstFetch(): ConstFetch|ClassConstFetch
    {
        return $this->constFetch;
    }
}
