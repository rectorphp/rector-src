<?php

namespace Rector\Tests\Php74\Rector\Variable\CurlyToSquareBracketArrayStringRector\Fixture;

class Fixture
{
    public function runString(string $string)
    {
        return $string{0};
    }

    public function runArray(array $array)
    {
        return $array{0};
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Variable\CurlyToSquareBracketArrayStringRector\Fixture;

class Fixture
{
    public function runString(string $string)
    {
        return $string[0];
    }

    public function runArray(array $array)
    {
        return $array[0];
    }
}

?>
