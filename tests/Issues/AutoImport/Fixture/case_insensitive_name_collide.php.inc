<?php

declare(strict_types=1);

final class CaseInsensitiveNameCollide
{
    public function aMethod()
    {
        \urls\Needprofile::doSomething();
    }

    public function aMethod2()
    {
        \stuff\NeedProfile::doSomething();
    }
}

?>
-----
<?php

declare(strict_types=1);

use urls\Needprofile;

final class CaseInsensitiveNameCollide
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

?>
