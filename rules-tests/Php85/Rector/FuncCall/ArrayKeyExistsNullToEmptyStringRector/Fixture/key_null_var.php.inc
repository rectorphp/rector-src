<?php

namespace Rector\Tests\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector\Fixture;

array_key_exists($k, $array);

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector\Fixture;

array_key_exists((string) $k, $array);

?>