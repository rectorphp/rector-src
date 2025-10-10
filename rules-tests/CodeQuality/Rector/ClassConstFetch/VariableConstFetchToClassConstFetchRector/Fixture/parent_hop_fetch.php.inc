<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ChildClassNotWithConstant;

final class ParentHopFetch
{
    public function run(ChildClassNotWithConstant $classWithConstants)
    {
        return $classWithConstants::NAME;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ChildClassNotWithConstant;

final class ParentHopFetch
{
    public function run(ChildClassNotWithConstant $classWithConstants)
    {
        return \Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ChildClassNotWithConstant::NAME;
    }
}

?>
