<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\IssueConstructorPromotionRename\Fixture;

use Rector\Tests\Issues\IssueConstructorPromotionRename\Source\PromotedPropertyObject;

final class Fixture
{
    private $a;

    public function __construct(PromotedPropertyObject $a)
    {
        $this->a = $a;
        dump($a);
        dump($a->someCall('Y-m-d'));
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\IssueConstructorPromotionRename\Fixture;

use Rector\Tests\Issues\IssueConstructorPromotionRename\Source\PromotedPropertyObject;

final class Fixture
{
    public function __construct(private PromotedPropertyObject $promotedPropertyObject)
    {
        dump($this->promotedPropertyObject);
        dump($this->promotedPropertyObject->someCall('Y-m-d'));
    }
}

?>
