<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class IncludeMissingReturnArray
{
    public function getNames()
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

final class IncludeMissingReturnArray
{
    /**
     * @return array<string, string>
     */
    public function getNames()
    {
        return [
            'key' => 'value',
        ];
    }
}

?>
