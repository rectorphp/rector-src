<?php

namespace Rector\Core\Tests\Issues\DefaultValueOptionalThrow\Fixture;

use Exception;

class DoNotAddInitValue
{
    public function run()
    {
        try {
            return 1;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\DefaultValueOptionalThrow\Fixture;

use Exception;

class DoNotAddInitValue
{
    public function run()
    {
        try {
            return 1;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
}

?>
