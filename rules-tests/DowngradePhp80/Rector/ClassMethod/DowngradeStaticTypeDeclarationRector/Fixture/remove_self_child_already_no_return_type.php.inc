<?php

namespace Rector\Tests\DowngradePhp80\Rector\ClassMethod\DowngradeStaticTypeDeclarationRector\Fixture;

interface RemoveSelfChildAlreadyNoType
{
    public function run(): self;
}

class Child implements RemoveSelfChildAlreadyNoType
{
    // return type may removed by other rules
    public function run()
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\ClassMethod\DowngradeStaticTypeDeclarationRector\Fixture;

interface RemoveSelfChildAlreadyNoType
{
    public function run()
}

class Child implements RemoveSelfChildAlreadyNoType
{
    // return type may removed by other rules
    public function run()
    {

    }
}

?>
