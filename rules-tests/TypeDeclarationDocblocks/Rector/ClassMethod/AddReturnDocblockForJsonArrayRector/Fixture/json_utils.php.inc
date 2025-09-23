<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector\Fixture;

use Nette\Utils\Json;

final class JsonUtils
{
    public function provide(string $contents): array
    {
        return Json::decode($contents, true);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector\Fixture;

use Nette\Utils\Json;

final class JsonUtils
{
    /**
     * @return array<string, mixed>
     */
    public function provide(string $contents): array
    {
        return Json::decode($contents, true);
    }
}

?>
