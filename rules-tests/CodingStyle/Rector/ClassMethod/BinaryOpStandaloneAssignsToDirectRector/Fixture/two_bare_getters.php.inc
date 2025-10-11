<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Fixture;

use CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Source\SomeGetter;

final class TwoBareGetters
{
    public function run(SomeGetter $firstSomeGetter, SomeGetter $secondSomeGetter)
    {
        $first = $firstSomeGetter->getSome();
        $second = $secondSomeGetter->getSome();

        return $first <=> $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Fixture;

use CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Source\SomeGetter;

final class TwoBareGetters
{
    public function run(SomeGetter $firstSomeGetter, SomeGetter $secondSomeGetter)
    {
        return $firstSomeGetter->getSome() <=> $secondSomeGetter->getSome();
    }
}

?>
