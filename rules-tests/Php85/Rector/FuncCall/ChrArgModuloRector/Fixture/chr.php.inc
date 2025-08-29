<?php
namespace Rector\Tests\Php85\Rector\FuncCall\ChrArgModuloRector\Fixture;

echo chr(300);
?>
-----
<?php
namespace Rector\Tests\Php85\Rector\FuncCall\ChrArgModuloRector\Fixture;

echo chr(300 % 256);
?>