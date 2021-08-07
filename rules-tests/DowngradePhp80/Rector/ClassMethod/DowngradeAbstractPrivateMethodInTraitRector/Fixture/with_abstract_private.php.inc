<?php

namespace Rector\Tests\DowngradePhp80\Rector\ClassMethod\DowngradeAbstractPrivateMethodInTraitRector\Fixture;

trait WithAbstractPrivate
{
    abstract private function doNotTouch();
}
?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\ClassMethod\DowngradeAbstractPrivateMethodInTraitRector\Fixture;

trait WithAbstractPrivate
{
    private function doNotTouch() {}
}
?>
