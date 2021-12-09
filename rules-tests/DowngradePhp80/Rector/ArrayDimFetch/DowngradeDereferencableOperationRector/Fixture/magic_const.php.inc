<?php

namespace Rector\Tests\DowngradePhp80\Rector\ArrayDimFetch\DowngradeDereferencableOperationRector\Fixture;

var_dump(__DIR__[0] === '/');

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\ArrayDimFetch\DowngradeDereferencableOperationRector\Fixture;

var_dump((__DIR__)[0] === '/');

?>
