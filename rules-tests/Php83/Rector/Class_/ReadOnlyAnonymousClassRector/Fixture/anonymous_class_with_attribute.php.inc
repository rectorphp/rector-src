<?php

namespace Rector\Tests\Php83\Rector\Class_\ReadOnlyAnonymousClassRector\Fixture;

$attributed = new #[Tries(5)] class(4)
{
    public function __construct(
        private readonly int $attempts,
    ) {}

    public function attempts(): int
    {
        return $this->attempts;
    }
};

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\Class_\ReadOnlyAnonymousClassRector\Fixture;

$attributed = new #[Tries(5)] readonly class(4)
{
    public function __construct(
        private int $attempts,
    ) {}

    public function attempts(): int
    {
        return $this->attempts;
    }
};

?>