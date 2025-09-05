<?php 
namespace Rector\Tests\Php85\Rector\FuncCall\OrdSingleByteRector\Fixture;
$a = 'abc';
echo ord($a);
?>
-----
<?php 
namespace Rector\Tests\Php85\Rector\FuncCall\OrdSingleByteRector\Fixture;
$a = 'abc';
echo ord($a[0]);
?>