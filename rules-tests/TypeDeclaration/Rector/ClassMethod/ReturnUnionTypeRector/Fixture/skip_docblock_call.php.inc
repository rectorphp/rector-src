<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use stdClass;

final class SkipDocblockCall
{
    public function run()
    {
        return $this->execute();
    }

    /**
     * @return null|stdClass
     */
    private function execute()
    {
    }
}
