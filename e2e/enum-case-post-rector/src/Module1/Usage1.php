<?php

namespace Rector\Tests\e2e\EnumCasePostRector\Module1;

class Usage1
{
    public function isValid(Status $status): bool
    {
        return $status === Status::APPROVED;
    }
}
