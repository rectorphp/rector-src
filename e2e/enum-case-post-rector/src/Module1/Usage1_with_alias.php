<?php

namespace Rector\Tests\e2e\EnumCasePostRector\Module1;

use Rector\Tests\e2e\EnumCasePostRector\Module1\Status as StatusAlias;

class Usage1
{
    public function isValid(StatusAlias $status): bool
    {
        return $status === StatusAlias::APPROVED;
    }
}
