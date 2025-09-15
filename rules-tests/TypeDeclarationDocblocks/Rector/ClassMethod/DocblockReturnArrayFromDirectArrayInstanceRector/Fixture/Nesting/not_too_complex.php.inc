<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

final class AvoidExtremeNesting
{
   public function run(): array
    {
        return [
            'receipts' => 1000,
            'bookStock' => '1000.0',
            'dipStock' => 100.5,
            'lossGain' => -1000,
            'lossGainPercentage' => '1000%',
            'accumulatedLossGain' => -135000,
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

final class AvoidExtremeNesting
{
   /**
    * @return array<string, int|string|float>
    */
   public function run(): array
    {
        return [
            'receipts' => 1000,
            'sales' => 1000,
            'bookStock' => '1000.0',
            'dipStock' => 100.5,
            'lossGain' => -1000,
            'lossGainPercentage' => '1000%',
            'accumulatedLossGain' => -135000,
        ];
    }
}

?>
