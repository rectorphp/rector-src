<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeFromTryCatchTypeRector\Fixture;

final class SimpleTryCatch
{
    public function run()
    {
        try {
            return 1;
        } catch (\Exception $e) {
            return $this->getIntDoc();
        }
    }

    /**
     * @return int
     */
    private function getIntDoc()
    {
        return mt_rand(0, 1) ? 'string' : 1;
    }
}
