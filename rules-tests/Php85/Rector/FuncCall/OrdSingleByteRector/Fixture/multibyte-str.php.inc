<?php 
namespace Rector\Tests\Php85\Rector\FuncCall\OrdSingleByteRector\Fixture;
echo ord('abc');
?>
-----
<?php 
namespace Rector\Tests\Php85\Rector\FuncCall\OrdSingleByteRector\Fixture;
echo ord('a');
?>