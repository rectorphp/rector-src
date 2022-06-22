<?php

namespace Rector\Tests\DowngradePhp80\Rector\FunctionLike\DowngradeUnionTypeDeclarationRector\Fixture;

final class DoNotChangeCorrectUnionArrayDocblockOnParam
{
    /**
     * @param int|string[]
     */
    public function run(int|array $data)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\FunctionLike\DowngradeUnionTypeDeclarationRector\Fixture;

final class DoNotChangeCorrectUnionArrayDocblockOnParam
{
    /**
     * @param int|string[]
     */
    public function run($data)
    {
    }
}

?>
