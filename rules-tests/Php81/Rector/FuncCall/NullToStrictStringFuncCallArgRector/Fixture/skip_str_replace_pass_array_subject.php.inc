<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipStrReplacePassArraySubject
{
    public function __construct(array $roles)
    {
        return str_replace('PRIVILEGE_', '', $roles);
    }
}
