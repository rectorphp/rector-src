<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipAfterIsStringOnObjectCall
{
    public ?string $stringNull = 'foo';
}

$demo = new SkipAfterIsStringOnObjectCall();

if (is_string($demo->stringNull) && trim($demo->stringNull) !== '') {
    echo $demo->stringNull;
}
