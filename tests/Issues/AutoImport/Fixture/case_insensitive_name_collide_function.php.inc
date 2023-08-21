<?php

declare(strict_types=1);

final class CaseInsensitiveNameCollideFunction
{
    public function aMethod()
    {
        \urls\Needprofile();
    }

    public function aMethod2()
    {
        \stuff\NeedProfile();
    }
}

?>
-----
<?php

declare(strict_types=1);

use function urls\Needprofile;

final class CaseInsensitiveNameCollideFunction
{
    public function aMethod()
    {
        Needprofile();
    }

    public function aMethod2()
    {
        \stuff\NeedProfile();
    }
}

?>
