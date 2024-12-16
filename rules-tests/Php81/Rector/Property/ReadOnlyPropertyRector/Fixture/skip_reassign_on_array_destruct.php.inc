<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

class SkipReassignOnArrayDestruct
{
    private int $selectedFrom;
    private int $selectedTo;

    public function run()
    {
        [$this->selectedFrom, $this->selectedTo] = [0, 1];
        [$this->selectedFrom, $this->selectedTo] = [0, 1];
    }
}
