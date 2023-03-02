<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class SkipArrayAppendDifferentKeysInCase
{
    public function run()
    {
        switch ($file['type']) {
            case 'directory':
                $root['folders'][] = $file;
                break;
            case 'file':
                $root['files'][] = $file;
                break;
            default:
                $root['unknown'][] = $file;
        }
    }
}

?>
