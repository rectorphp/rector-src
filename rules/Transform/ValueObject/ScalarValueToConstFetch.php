<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Scalar\Float_;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;

final readonly class ScalarValueToConstFetch
{
    public function __construct(
        private Float_|String_|Int_ $scalar,
        private ConstFetch|ClassConstFetch $constFetch
    ) {
    }

    public function getScalar(): Float_|String_|Int_
    {
        return $this->scalar;
    }

    public function getConstFetch(): ConstFetch|ClassConstFetch
    {
        return $this->constFetch;
    }
}
