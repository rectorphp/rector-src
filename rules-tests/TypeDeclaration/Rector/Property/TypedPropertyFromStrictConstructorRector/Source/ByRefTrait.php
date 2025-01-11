<?php
declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Source;

trait ByRefTrait
{
    public function run()
    {
        $str = &$this->str;
        $str = null;
    }
}
