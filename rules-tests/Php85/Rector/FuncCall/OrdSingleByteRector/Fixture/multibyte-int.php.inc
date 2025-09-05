<?php 
namespace Rector\Tests\Php85\Rector\FuncCall\OrdSingleByteRector\Fixture;
echo ord(123);
?>
-----
<?php 
namespace Rector\Tests\Php85\Rector\FuncCall\OrdSingleByteRector\Fixture;
echo ord(1);
?>