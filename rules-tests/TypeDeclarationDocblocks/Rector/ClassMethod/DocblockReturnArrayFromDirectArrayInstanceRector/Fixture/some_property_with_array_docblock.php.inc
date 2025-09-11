<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class SomePropertyWithArrayDocblock
{
    public function getNames(): array
    {
        return [
            'key' => 'value',
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class SomePropertyWithArrayDocblock
{
    /**
     * @return array<string, string>
     */
    public function getNames(): array
    {
        return [
            'key' => 'value',
        ];
    }
}

?>
