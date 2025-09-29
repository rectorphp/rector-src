<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector\Fixture;

final class OverrideDummyArray
{
    /**
     * @return array
     */
    public function provide(string $contents): array
    {
        return json_decode($contents, true);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector\Fixture;

final class OverrideDummyArray
{
    /**
     * @return array<string, mixed>
     */
    public function provide(string $contents): array
    {
        return json_decode($contents, true);
    }
}

?>
