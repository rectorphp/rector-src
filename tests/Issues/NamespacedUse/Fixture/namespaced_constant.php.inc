<?php

namespace Apple {
    const Banana = 'test';
}

namespace {

    use const Foo\Bar;
    use const Baz\bar;
    use const apple\Banana;

    var_dump(bar);
    var_dump(Banana);
}

?>
-----
<?php

namespace Apple {
    const Banana = 'test';
}

namespace {

    use const Baz\bar;
    use const apple\Banana;

    var_dump(bar);
    var_dump(Banana);
}

?>
