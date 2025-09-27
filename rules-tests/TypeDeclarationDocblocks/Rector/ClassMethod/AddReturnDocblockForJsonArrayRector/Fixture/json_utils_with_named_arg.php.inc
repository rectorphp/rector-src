<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector\Fixture;

use Nette\Utils\Json;

final class JsonUtilsWithNamedArg
{
    public function provide(string $contents): array
    {
        return Json::decode(forceArrays: true, json: $contents);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector\Fixture;

use Nette\Utils\Json;

final class JsonUtilsWithNamedArg
{
    /**
     * @return array<string, mixed>
     */
    public function provide(string $contents): array
    {
        return Json::decode(forceArrays: true, json: $contents);
    }
}

?>
