<?php

namespace Rector\Tests\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector\Source\AutoMailingService;

final class InlineVar
{
    public function run()
    {
        /** @var AutomailingService $service */
        $service = $this->getService();

        return $service;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector\Source\AutoMailingService;

final class InlineVar
{
    public function run()
    {
        /** @var AutoMailingService $service */
        $service = $this->getService();

        return $service;
    }
}

?>
