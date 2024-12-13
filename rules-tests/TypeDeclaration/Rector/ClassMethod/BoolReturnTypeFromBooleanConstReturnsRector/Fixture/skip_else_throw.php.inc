<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstReturnsRector\Fixture;

final class SkipElseThrow
{
    /**
     * Reset controller to a given one.
     *
     * @param string $controller
     *
     * @return no-return
     */
    public function reset_controller($controller)
    {
        if (!class_exists($controller, true)) {
            return false;
        }
        echo 'ABC';
        throw new \Exception();
    }
}