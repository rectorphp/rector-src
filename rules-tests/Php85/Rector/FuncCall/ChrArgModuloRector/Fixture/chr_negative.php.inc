<?php
namespace Rector\Tests\Php85\Rector\FuncCall\ChrArgModuloRector\Fixture;

echo chr(-1);
echo chr(-300);
echo chr(-256);
?>
-----
<?php
namespace Rector\Tests\Php85\Rector\FuncCall\ChrArgModuloRector\Fixture;

echo chr(-1 % 256 + 256);
echo chr(-300 % 256 + 256);
echo chr(-256 % 256);
?>
