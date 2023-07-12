<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class StringArrayUnion
{
    /**
     * @param string|array $ids
     */
    private function getLabelsForIds($ids)
    {
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        if (! isset($ids[0])) {
            return '';
        }

        if ('alle' === $ids[0]) {
            return $ids[0];
        }
    }
}

?>
