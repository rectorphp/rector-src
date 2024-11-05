<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Source\Performance;

/**
 * @template TPerformance of Performance
 */
interface SkipNullableTemplateTag {
    /** @return TPerformance|null */
    public function performance(): Performance|null;
}
