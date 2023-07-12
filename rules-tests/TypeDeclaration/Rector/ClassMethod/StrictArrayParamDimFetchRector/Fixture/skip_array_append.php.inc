<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipArrayAppend
{
    public function addSuccessMessage($success)
    {
        $this->success[] = $success;
    }
}
