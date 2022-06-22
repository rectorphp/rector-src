<?php

namespace Rector\Tests\DowngradePhp80\Rector\FunctionLike\DowngradeUnionTypeDeclarationRector\Fixture;

final class DoNotChangeCorrectUnionArrayDocblock
{
    /**
     * @return int|string[]
     */
    public function run(): int|array
    {
        if (mt_rand()) {
            return 1;
        }

        return ['test'];
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\FunctionLike\DowngradeUnionTypeDeclarationRector\Fixture;

final class DoNotChangeCorrectUnionArrayDocblock
{
    /**
     * @return int|string[]
     */
    public function run()
    {
        if (mt_rand()) {
            return 1;
        }

        return ['test'];
    }
}

?>
