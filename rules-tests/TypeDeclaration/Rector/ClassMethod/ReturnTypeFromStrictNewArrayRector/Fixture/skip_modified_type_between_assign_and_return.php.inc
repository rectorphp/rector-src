<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class SkipModifiedTypeBetweenAssignAndReturn
{
    public function run()
    {
        $values = [];

        $this->settings($values);

        return $values;
    }

    private function settings(&$values)
    {
        $values = 1;
    }
}
