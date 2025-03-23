<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class SkipCaseAfterDefaultEmpty
{
    public function run(string $type)
    {
        switch ($type) {
            case 'day':
                $format = '%Y-%m-%d';
                break;
            default:
            case 'hour':
                $format = '%Y-%m-%d %H';
                break;
        }

        return $format;
    }
}
