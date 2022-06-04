<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Source\vendor;

use stdClass;

class ExternalParentNoReturnType
{
    public function run()
    {
        return new stdClass;
    }
}
