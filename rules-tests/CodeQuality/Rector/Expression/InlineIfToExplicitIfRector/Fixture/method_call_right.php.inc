<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector\Fixture;

class MethodCallRigth
{
    public function run()
    {
        $this->rowData === null || $this->dataSeek();
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector\Fixture;

class MethodCallRigth
{
    public function run()
    {
        if ($this->rowData !== null) {
            $this->dataSeek();
        }
    }
}

?>
