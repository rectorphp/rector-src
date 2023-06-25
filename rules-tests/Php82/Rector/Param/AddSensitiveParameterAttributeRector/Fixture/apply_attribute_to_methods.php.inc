<?php

namespace Rector\Tests\Php82\Rector\Param\AddSensitiveParameterAttributeRector\Fixture;

class Secure
{
    public function login($username, $password): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Param\AddSensitiveParameterAttributeRector\Fixture;

class Secure
{
    public function login($username, #[\SensitiveParameter]
    $password): void
    {
    }
}

?>
