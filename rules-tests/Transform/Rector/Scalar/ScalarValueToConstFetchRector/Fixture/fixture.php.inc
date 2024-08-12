<?php

namespace Rector\Tests\Transform\Rector\New_\NewToStaticCallRector\Fixture;

$int = 10;
$float = 10.1;
$string = 'ABC';

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\New_\NewToStaticCallRector\Fixture;

$int = \Rector\Tests\Transform\Rector\Scalar\ScalarValueToConstFetchRector\Source\ClassWithConst::FOOBAR_INT;
$float = \Rector\Tests\Transform\Rector\Scalar\ScalarValueToConstFetchRector\Source\ClassWithConst::FOOBAR_FLOAT;
$string = \Rector\Tests\Transform\Rector\Scalar\ScalarValueToConstFetchRector\Source\ClassWithConst::FOOBAR_STRING;

?>
