<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Manual\Twig\TwigFilter;

final class SomeKeepReturnTag
{
    /**
     * @return TwigFilter
     */
    public function keep()
    {
        return new TwigFilter();
    }
}
