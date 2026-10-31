<?php

namespace Rector\Tests\Issues\ImportedNameInNextPass\Fixture;

use Rector\Tests\Issues\ImportedNameInNextPass\Source\ArrayObject;

final class NullableFinalClassParam
{
    public function run(?ArrayObject $arrayObject): void
    {
        if ($arrayObject === null) {
            return;
        }

        echo 'set';
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ImportedNameInNextPass\Fixture;

use Rector\Tests\Issues\ImportedNameInNextPass\Source\ArrayObject;

final class NullableFinalClassParam
{
    public function run(?ArrayObject $arrayObject): void
    {
        if (!$arrayObject instanceof ArrayObject) {
            return;
        }

        echo 'set';
    }
}

?>
