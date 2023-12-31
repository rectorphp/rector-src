<?php

namespace Rector\Tests\Issues\Issue6840\Fixture;

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

namespace Rector\Tests\Issues\Issue6840\Fixture;

class Fixture
{
    protected array $imageSize;

    public function __construct(protected string $imageId)
    {
        $this->imageSize = [];
    }
}
?>
