<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ChildClassNotWithConstant;

final class AllowOverride
{
    public function run(ChildClassNotWithConstant $classWithConstants)
    {
        return $classWithConstants::ORIGINAL_VALUE;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ChildClassNotWithConstant;

final class AllowOverride
{
    public function run(ChildClassNotWithConstant $classWithConstants)
    {
        return \Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ChildClassNotWithConstant::ORIGINAL_VALUE;
    }
}

?>
