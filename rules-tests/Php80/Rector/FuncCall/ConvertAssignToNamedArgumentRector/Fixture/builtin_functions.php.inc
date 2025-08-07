<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

str_replace($search = 'old', $replace = 'new', $subject = 'old text');
substr($string = 'hello', $offset = 1, $length = 3);

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

str_replace(search: 'old', replace: 'new', subject: 'old text');
substr(string: 'hello', offset: 1, length: 3);

?>
