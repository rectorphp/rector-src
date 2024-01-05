<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipReassignWithCall
{
    public function addSuccessMessage($maybe_not_array)
    {
        $maybe_not_array = $this->convert($maybe_not_array);
        echo $maybe_not_array['test'];
    }

    private function convert($maybe_not_array)
    {
        return ['test' => 'test'];
    }
}
