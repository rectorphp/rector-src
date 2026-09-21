<?php

namespace Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\FirstTrait;
use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\SecondTrait;

class WithTraitUsePrecedence
{
    use FirstTrait, SecondTrait {
        FirstTrait::exec insteadof SecondTrait;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\FirstTrait;
use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\SecondTrait;

class WithTraitUsePrecedence
{
    use FirstTrait {
        FirstTrait::exec insteadof SecondTrait;
    }
    use SecondTrait;
}

?>
