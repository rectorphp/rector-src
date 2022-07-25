<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\FixtureFirstClassCallable;

final class FirstClassCallableAsKey
{
    public function getCallables(): array
    {
        return [
            $this->opposite(...) => 'test',
        ];
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\FixtureFirstClassCallable;

final class FirstClassCallableAsKey
{
    public function getCallables(): array
    {
        return [
            \Closure::fromCallable([$this, 'opposite']) => 'test',
        ];
    }
}

?>
