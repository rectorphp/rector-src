<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class SkipPhpdocs
{
    public function getValue()
    {
        $x = false;
        if (rand(0,1)) {
            $x = $this->get();
        }
        return $x ?: null;
    }

    /**
     * @return SkipPhpdocs
     */
    public function get() {}
}

?>
