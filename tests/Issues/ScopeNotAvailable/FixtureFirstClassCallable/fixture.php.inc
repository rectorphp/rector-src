<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\FixtureFirstClassCallable;

final class Fixture
{
    public function getCallables(): array
    {
        return [
            $this->opposite(...),
        ];
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\FixtureFirstClassCallable;

final class Fixture
{
    public function getCallables(): array
    {
        return [
            \Closure::fromCallable([$this, 'opposite']),
        ];
    }
}

?>
