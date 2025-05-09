<?php

namespace Rector\Tests\e2e\EnumCasePostRector\Module1;

class Usage2
{
    public function isValid(\Rector\Tests\e2e\EnumCasePostRector\Module2\Status $status): bool
    {
        return $status === \Rector\Tests\e2e\EnumCasePostRector\Module2\Status::APPROVED;
    }
}
