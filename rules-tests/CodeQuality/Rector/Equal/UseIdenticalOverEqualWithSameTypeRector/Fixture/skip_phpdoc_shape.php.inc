<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class SkipPhpdocArrayshape
{
    /**
     * @return array{'sql': string, 'files': array<string>}
     */
    public function getContent(): array
    {
    }

    public function import()
    {
        $content = $this->getContent();

        if (! isset($content['sql']) || $content['sql'] == '') {
        }
    }
}
