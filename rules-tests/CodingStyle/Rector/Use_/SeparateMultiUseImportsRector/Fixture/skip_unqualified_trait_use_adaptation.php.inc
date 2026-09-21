<?php

namespace Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\FirstTrait;
use Rector\Tests\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector\Source\ThirdTrait;

class SkipUnqualifiedTraitUseAdaptation
{
    use FirstTrait, ThirdTrait {
        other as renamedOther;
    }
}
