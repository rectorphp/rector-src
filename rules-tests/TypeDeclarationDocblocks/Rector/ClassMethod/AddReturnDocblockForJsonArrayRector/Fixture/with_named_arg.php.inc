<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector\Fixture;

final class WithNamedArg
{
    public function provide(string $contents): array
    {
        return json_decode(associative: true, json: $contents);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector\Fixture;

final class WithNamedArg
{
    /**
     * @return array<string, mixed>
     */
    public function provide(string $contents): array
    {
        return json_decode(associative: true, json: $contents);
    }
}

?>
