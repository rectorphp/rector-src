<?php

namespace Rector\Tests\DowngradePhp80\Rector\FunctionLike\DowngradeMixedTypeDeclarationRector\Fixture;

use Rector\Tests\DowngradePhp80\Rector\FunctionLike\DowngradeMixedTypeDeclarationRector\Source\Options;

class ArrayAccessOffsetGet extends Options
{
    public function offsetGet(): mixed
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\FunctionLike\DowngradeMixedTypeDeclarationRector\Fixture;

use Rector\Tests\DowngradePhp80\Rector\FunctionLike\DowngradeMixedTypeDeclarationRector\Source\Options;

class ArrayAccessOffsetGet extends Options
{
    /**
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet()
    {
    }
}

?>
