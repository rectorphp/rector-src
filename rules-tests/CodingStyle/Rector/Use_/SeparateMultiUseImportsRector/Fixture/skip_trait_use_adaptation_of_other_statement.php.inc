<?php

namespace Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\FirstTrait;
use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\SecondTrait;
use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\ThirdTrait;

class SkipTraitUseAdaptationOfOtherStatement
{
    use FirstTrait;
    use SecondTrait, ThirdTrait {
        FirstTrait::exec insteadof SecondTrait;
    }
}
