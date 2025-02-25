<?php

namespace Rector\Tests\Php74\Rector\ArrayDimFetch\CurlyToSquareBracketArrayStringRector\Fixture;

trait OnTrait
{
    public function runString(string $string)
    {
        return $string{0};
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\ArrayDimFetch\CurlyToSquareBracketArrayStringRector\Fixture;

trait OnTrait
{
    public function runString(string $string)
    {
        return $string[0];
    }
}

?>
