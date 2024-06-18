<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObject;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;

final readonly class ParamRename
{
    public function __construct(
        private string $currentName,
        private string $expectedName,
        private Param $param,
        private Variable $variable,
        private FunctionLike $functionLike
    ) {
    }

    public function getCurrentName(): string
    {
        return $this->currentName;
    }

    public function getExpectedName(): string
    {
        return $this->expectedName;
    }

    public function getFunctionLike(): FunctionLike
    {
        return $this->functionLike;
    }

    public function getParam(): Param
    {
        return $this->param;
    }

    public function getVariable(): Variable
    {
        return $this->variable;
    }
}
