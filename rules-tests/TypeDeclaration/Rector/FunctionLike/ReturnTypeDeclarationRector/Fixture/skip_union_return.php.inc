<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

use stdClass;

class SkipUnionReturn
{
    public function get($id) {
        if (rand(0,1)) {
            return new stdClass();
        }

        return [];
    }

    /**
     * @return stdClass|array
     */
    public function get2($id) {
        if (rand(0,1)) {
            return new stdClass();
        }

        return [];
    }
}
