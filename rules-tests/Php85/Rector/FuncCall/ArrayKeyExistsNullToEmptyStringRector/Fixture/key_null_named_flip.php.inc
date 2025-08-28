<?php

namespace Rector\Tests\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector\Fixture;

array_key_exists(array: $array, key: null);

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector\Fixture;

array_key_exists(array: $array, key: '');

?>