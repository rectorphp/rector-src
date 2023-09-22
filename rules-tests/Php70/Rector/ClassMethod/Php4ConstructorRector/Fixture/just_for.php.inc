<?php

declare(strict_types=1);

// PHP4 code is not allowed to contain namespaces

final class JustFor
{
    public function JustFor()
    {
        for ($i = 100; $i < 100; $i++) {
        }
    }
}

?>
-----
<?php

declare(strict_types=1);

// PHP4 code is not allowed to contain namespaces

final class JustFor
{
    public function __construct()
    {
        for ($i = 100; $i < 100; $i++) {
        }
    }
}

?>
