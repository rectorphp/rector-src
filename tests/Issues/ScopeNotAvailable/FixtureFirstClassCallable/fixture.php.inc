<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\FixtureFirstClassCallable;

final class DemoFile
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

final class DemoFile
{
    public function getCallables(): array
    {
        return [
            \Closure::fromCallable([$this, 'opposite']),
        ];
    }
}

?>
