<?php

namespace Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\FirstTrait;
use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\SecondTrait;

class WithTraitUseAdaptation
{
    use FirstTrait, SecondTrait {
        FirstTrait::exec as execT1;
        SecondTrait::exec as execT2;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\FirstTrait;
use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\SecondTrait;

class WithTraitUseAdaptation
{
    use FirstTrait {
        FirstTrait::exec as execT1;
    }
    use SecondTrait {
        SecondTrait::exec as execT2;
    }
}

?>
