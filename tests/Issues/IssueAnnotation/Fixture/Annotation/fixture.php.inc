<?php

namespace Rector\Core\Tests\Issues\IssueAnnotation\Fixture\Annotation;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * An attribute to tell how a base type should be tagged.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class AutoconfigureTag extends Autoconfigure
{
    public function __construct(string $name = null, array $attributes = [])
    {
        parent::__construct(
            tags: [
                [$name ?? 0 => $attributes],
            ]
        );
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IssueAnnotation\Fixture\Annotation;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * An attribute to tell how a base type should be tagged.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class AutoconfigureTag extends Autoconfigure
{
    public function __construct(string $name = null, array $attributes = [])
    {
        parent::__construct(
            [
                [$name ?? 0 => $attributes],
            ], null, null, null, null, null, null, null
        );
    }
}

?>
