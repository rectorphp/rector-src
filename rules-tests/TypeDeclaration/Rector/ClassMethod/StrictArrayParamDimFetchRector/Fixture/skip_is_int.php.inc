<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipIsInt
{
    private function getLabelsForIds($ids)
    {
        if (is_int($ids)) {
            $ids = explode(',', $ids);
        }
        if (! isset($ids[0])) {
            return '';
        }

        if ('all' === $ids[0]) {
            return $ids[0];
        }
    }
}
