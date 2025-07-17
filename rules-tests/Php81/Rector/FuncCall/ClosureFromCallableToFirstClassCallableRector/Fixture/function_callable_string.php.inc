<?php

namespace Foo;

\Closure::fromCallable('trim');
\Closure::fromCallable('\trim');
\Closure::fromCallable('Psl\Str\trim');
\Closure::fromCallable('\Psl\Str\trim');

?>
-----
<?php

namespace Foo;

\trim(...);
\trim(...);
\Psl\Str\trim(...);
\Psl\Str\trim(...);

?>
