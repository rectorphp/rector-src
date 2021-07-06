<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

use stdClass;
use DateTime;

class SkipMixedUnionNeverType
{
    /**
     * @return stdClass[]|DateTime[]
     */
    public function provideForVendor(array $items): array
    {
        if (rand(0,1)) {
            return [];
        }

        /** @var stdClass[]|DateTime[] $propertyFetches */
        $value = $this->getData();

        return $value;
    }

    /**
     * @return \stdClass[]|\DateTime[]
     */
    private function getData()
    {
        if (rand(0,1)) {
            return [new stdClass()];
        }

        return [new DateTime()];
    }
}
