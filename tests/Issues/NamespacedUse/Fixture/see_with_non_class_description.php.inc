<?php

namespace Rector\Tests\Issues\NamespacedUse\Fixture;

use Rector\Renaming\Rector\Name\RenameClassRector;

/**
 * @see some description that does not start with a class reference
 */
final class SeeWithNonClassDescription
{
}
-----
<?php

namespace Rector\Tests\Issues\NamespacedUse\Fixture;

/**
 * @see some description that does not start with a class reference
 */
final class SeeWithNonClassDescription
{
}
