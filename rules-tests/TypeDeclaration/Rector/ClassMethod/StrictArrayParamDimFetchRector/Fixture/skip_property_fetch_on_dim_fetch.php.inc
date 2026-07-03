<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

class SkipPropertyFetchOnDimFetch
{
    /**
     * @param object $object
     */
    private function getDataForObject($object): mixed
    {
        foreach ($object as $key => $value) {
            if (0 === $key) {
                continue;
            }

            $object[0]->$key = $value;
        }

        return $object[0];
    }
}
