<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\Properties;

/**
 * @template TProperties of Properties|null
 * @template TCategories of object
 */
final class SkipTemplateTagWithMultipleTemplates
{
    /** @var TCategories */
    private object $categories;

    /** @var TProperties */
    private Properties|null $properties;
}
