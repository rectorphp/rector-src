<?php

namespace Rector\Tests\Issues\ScopeNotAvailable\FixtureJsonThrowCaseSensitiveConstFetch;

if (true) {
    return;
}

echo 'some statement 1';
echo 'some statement 2';

function foo($foo) {
    echo(json_encode($foo));
}

?>
-----
<?php

namespace Rector\Tests\Issues\ScopeNotAvailable\FixtureJsonThrowCaseSensitiveConstFetch;

if (true) {
    return;
}

echo 'some statement 1';
echo 'some statement 2';

function foo($foo) {
    echo(json_encode($foo, JSON_THROW_ON_ERROR));
}

?>
