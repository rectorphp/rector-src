<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

class SkipUnionVoid
{
    /**
     * @return false|self
     */
    private function find_one() {
        return false;
    }

    public function getRent()
    {
        if (random_int(0 ,1 )) {
            $rent = $this->find_one();

            return $rent ?: null;
        }
    }

}
