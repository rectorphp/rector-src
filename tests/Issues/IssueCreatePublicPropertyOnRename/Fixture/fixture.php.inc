<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssueCreatePublicPropertyOnRename\Fixture;

use Rector\Core\Tests\Issues\IssueCreatePublicPropertyOnRename\Source\SomePropertyObject;

final class Fixture
{
    public function __construct(private SomePropertyObject $date)
    {
    }

    public function run(): string
    {
        return $this->date->format('l, F j, Y');
    }
}
?>
-----
<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssueCreatePublicPropertyOnRename\Fixture;

use Rector\Core\Tests\Issues\IssueCreatePublicPropertyOnRename\Source\SomePropertyObject;

final class Fixture
{
    public function __construct(private SomePropertyObject $somePropertyObject)
    {
    }

    public function run(): string
    {
        return $this->somePropertyObject->format('l, F j, Y');
    }
}
?>
