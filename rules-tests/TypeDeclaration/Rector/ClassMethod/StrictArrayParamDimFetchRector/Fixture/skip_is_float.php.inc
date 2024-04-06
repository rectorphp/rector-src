<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipIsFloat
{
    private function getLabelsForIds($ids)
    {
        if (is_float($ids)) {
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
