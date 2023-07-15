<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class SkipWrongReturn
{
    public function getValue()
    {
        $x = false;
        if (rand(0,1)) {
            $x = $this->get();
        }

        if (rand(0,1)) {
            return $x ?: null;
        }

        // missing return
    }

    public function get(): SkipWrongReturn {}
}

?>
