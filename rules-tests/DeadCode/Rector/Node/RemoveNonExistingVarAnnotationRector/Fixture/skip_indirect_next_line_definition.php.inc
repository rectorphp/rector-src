<?php

namespace Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\Fixture;

use stdClass;

final class SkipIndirectNextlineDefinition
{
    public function get(array $stds)
    {
		/** @var stdClass[] $stds */
		$foundstd = null;
        foreach ($stds as $std) {
            if ($std->foo = 'foo') {
                $foundstd = true;
                break;
            }
        }
    }
}
