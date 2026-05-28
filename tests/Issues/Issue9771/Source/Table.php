<?php

namespace Rector\Tests\Issues\Issue9771\Source;

final class Table
{
    /**
     * @param int $height
     * @param mixed $style
     */
    public function addRow($height = null, $style = null)
    {
    }
}
