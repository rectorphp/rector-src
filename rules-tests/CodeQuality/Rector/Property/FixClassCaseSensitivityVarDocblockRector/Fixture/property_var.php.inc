<?php

namespace Rector\Tests\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector\Source\AutoMailingService;

final class PropertyVar
{
    /**
     * @var AUTOMAILINGSERVICE
     */
    private $service;
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector\Source\AutoMailingService;

final class PropertyVar
{
    /**
     * @var AutoMailingService
     */
    private $service;
}

?>
