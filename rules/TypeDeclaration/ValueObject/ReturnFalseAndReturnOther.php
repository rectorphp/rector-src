<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PhpParser\Node\Stmt\Return_;

final class ReturnFalseAndReturnOther
{
    public function __construct(
        private readonly Return_ $falseReturn,
        private readonly Return_ $otherReturn,
    ) {
    }

    public function getFalseReturn(): Return_
    {
        return $this->falseReturn;
    }

    public function getOtherReturn(): Return_
    {
        return $this->otherReturn;
    }
}
