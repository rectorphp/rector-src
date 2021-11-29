<?php

namespace Rector\Core\Tests\Issues\Issue6840\Fixture;

class Fixture
{
    protected string $imageId;
    protected array $imageSize;

    public function __construct($imageId)
    {
        $this->imageId = $imageId;
        $this->imageSize = [];
    }
}
?>
-----
<?php

namespace Rector\Core\Tests\Issues\Issue6840\Fixture;

class Fixture
{
    private array $imageSize;

    public function __construct(
        private string $imageId
    ) {
        $this->imageSize = [];
    }
}
?>
