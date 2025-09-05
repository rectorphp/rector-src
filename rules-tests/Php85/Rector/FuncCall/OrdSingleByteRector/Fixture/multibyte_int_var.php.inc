<?php 
namespace Rector\Tests\Php85\Rector\FuncCall\OrdSingleByteRector\Fixture;
$i = 123;
echo ord($i);
?>
-----
<?php 
namespace Rector\Tests\Php85\Rector\FuncCall\OrdSingleByteRector\Fixture;
$i = 123;
echo ord(1);
?>