<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class SkipDifferentVariable
{
    public function create($variable, $type, $data)
    {
        $someVar[$variable] = (float) $data;

         if (rand(0, 1)) {
            $someVar[sprintf('%', $type)] = 0.0;
            $someVar[$type] = 0.0;

            return $someVar;
         }
    }
}