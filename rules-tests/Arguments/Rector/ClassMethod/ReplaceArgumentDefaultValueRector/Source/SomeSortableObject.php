<?php

declare(strict_types=1);

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Source;

class SomeSortableObject
{
    public const SORT_ORDER_ASC = 'ASC';

    public const SORT_ORDER_DESC = 'DESC';

    public function sortBy(string $dir)
    {
    }
}
