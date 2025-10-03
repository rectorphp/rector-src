<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class SkipIdenticalNumericString
{
    public function equal()
    {
        if ($this->getValue() == '1') {
            return 'yes';
        }

        return 'no';
    }

    private function getValue(): string
    {
        return '1';
    }
}
