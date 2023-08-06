<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use stdClass;

final class SkipFromDocblockParam
{
    /**
     * @param null|stdClass
     */
    public function run($param)
    {
        return $param;
    }
}
