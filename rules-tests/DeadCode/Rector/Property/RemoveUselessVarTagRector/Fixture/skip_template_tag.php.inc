<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\Properties;

/**
 * @template TProperties of Properties|null
 */
final class SkipTemplateTag
{
    /** @var TProperties */
    private Properties|null $properties;
}
