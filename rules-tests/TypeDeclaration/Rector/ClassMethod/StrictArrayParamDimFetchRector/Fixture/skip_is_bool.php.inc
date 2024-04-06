<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipIsBool
{
    private function getLabelsForIds($ids)
    {
        if (is_bool($ids)) {
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
