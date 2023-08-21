<?php

declare(strict_types=1);

use urls\Needprofile;

final class SkipCaseInsensitiveNameAlreadyInUse
{
    public function aMethod()
    {
        Needprofile::doSomething();
    }

    public function aMethod2()
    {
        \stuff\NeedProfile::doSomething();
    }
}