<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

substr($string = str_repeat('a', $times = 10), $offset = 2, $length = 5);

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

substr(string: str_repeat('a', times: 10), offset: 2, length: 5);

?>
