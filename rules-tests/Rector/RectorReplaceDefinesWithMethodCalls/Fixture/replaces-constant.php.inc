<?php
$testVar = BAZ_DEFINE > 1 ? 1 : 0;
?>
-----
<?php
$testVar = \Tests\App::getDefine('BAZ_DEFINE') > 1 ? 1 : 0;
?>
