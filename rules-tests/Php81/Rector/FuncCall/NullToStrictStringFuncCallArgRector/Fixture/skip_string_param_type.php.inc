<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipStringParamType
{
    /**
     * @param string $subject
     */
    public function run($subject)
    {
        preg_split('#a#', $subject);
    }
}
