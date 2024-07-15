<?php

namespace Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\FirstTrait;
use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\SecondTrait;

class WithTraitUseAdaptationFlipped
{
    use FirstTrait, SecondTrait {
        SecondTrait::exec as execT2;
        FirstTrait::exec as execT1;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\FirstTrait;
use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\SecondTrait;

class WithTraitUseAdaptationFlipped
{
    use FirstTrait {
        FirstTrait::exec as execT1;
    }
    use SecondTrait {
        SecondTrait::exec as execT2;
    }
}

?>
