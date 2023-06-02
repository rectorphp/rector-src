<?php

namespace A {
    use Rector\Core\Tests\Issues\AutoImportGroupUse\Source\{ SomeClass };

    final class AutoImportInGroupUse extends \Rector\Core\Tests\Issues\AutoImportGroupUse\Source\SomeClass
    {
    }
}

namespace B {
    final class AutoImportInGroupUse extends \Rector\Core\Tests\Issues\AutoImportGroupUse\Source\SomeClass
    {
    }
}
