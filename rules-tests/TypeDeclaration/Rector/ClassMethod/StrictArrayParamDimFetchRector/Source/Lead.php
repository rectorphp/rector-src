<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Source;

final class Lead
{
    /**
     * @return mixed[]
     */
    public function getPrimaryCompany(): array
    {
        return [];
    }
}
