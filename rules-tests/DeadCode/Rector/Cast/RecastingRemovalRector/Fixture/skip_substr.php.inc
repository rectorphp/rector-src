<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

/**
 * On PHP 7.4, substr() can returns false
 */
class SkipSubstr
{
    public function run(): string
    {
        $className = 'Exception';
        return (string) substr($className, 0, -strlen($className) - 1);
    }
}

?>
