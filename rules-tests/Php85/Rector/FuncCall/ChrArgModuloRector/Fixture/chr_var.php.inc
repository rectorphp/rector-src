<?php
namespace Rector\Tests\Php85\Rector\FuncCall\ChrArgModuloRector\Fixture;

$n = 400;
echo chr($n);
?>
-----
<?php
namespace Rector\Tests\Php85\Rector\FuncCall\ChrArgModuloRector\Fixture;

$n = 400;
echo chr($n % 256);
?>