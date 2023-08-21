<?php

declare(strict_types=1);

use function urls\Needprofile;

final class SkipCaseInsensitiveNameAlreadyInUseFunction
{
    public function aMethod()
    {
        Needprofile();
    }

    public function aMethod2()
    {
        \stuff\NeedProfile::doSomething();
    }
}
