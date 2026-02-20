<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ClassWithFinalConstant;

final class AllowFinalClassConstant
{
    public function run(ClassWithFinalConstant $classWithFinalConstant)
    {
        return $classWithFinalConstant::NAME;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ClassWithFinalConstant;

final class AllowFinalClassConstant
{
    public function run(ClassWithFinalConstant $classWithFinalConstant)
    {
        return \Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ClassWithFinalConstant::NAME;
    }
}

?>
