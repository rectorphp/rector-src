<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\Enum;

enum SomeEnumWithMethod: string
{
    case FIRST = 'FiRsT';

    public function oldEnumMethod()
    {
    }
}
