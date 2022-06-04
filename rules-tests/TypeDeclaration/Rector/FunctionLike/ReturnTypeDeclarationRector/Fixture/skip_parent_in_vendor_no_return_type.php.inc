<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Source\vendor\ExternalParentNoReturnType;
use stdClass;

final class SkipParentInVendorNoReturnType extends ExternalParentNoReturnType
{
    public function run()
    {
        return new stdClass;
    }
}
