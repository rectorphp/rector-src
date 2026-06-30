<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipChangedByRefInForeach
{
    private array $relativeDateStrings;

    public function __construct()
    {
        $this->relativeDateStrings = ['yesterday', 'today'];
        foreach ($this->relativeDateStrings as &$string) {
            $string = strtoupper($string);
        }
    }
}
