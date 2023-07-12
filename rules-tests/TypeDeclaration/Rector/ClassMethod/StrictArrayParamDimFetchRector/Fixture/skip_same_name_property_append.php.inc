<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipSameNamePropertyFetch
{
    public function addSuccessMessage($success)
    {
        $this->success[] = $success;
    }
}
