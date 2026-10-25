<?php

namespace Rector\Tests\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector\Fixture;

final class KeyNull
{
    public function run($array)
    {
        array_key_exists(null, $array);

        array_key_exists(array: $array, key: null);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector\Fixture;

final class KeyNull
{
    public function run($array)
    {
        array_key_exists('', $array);

        array_key_exists(array: $array, key: '');
    }
}

?>
