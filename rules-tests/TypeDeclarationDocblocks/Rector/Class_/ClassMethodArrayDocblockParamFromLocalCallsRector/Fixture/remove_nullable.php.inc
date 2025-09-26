<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class RemoveNullable
{
    public function go()
    {
        $this->run($this->getNames());
    }

    private function run(array $items)
    {
    }

    /**
     * @return string[]|null
     */
    private function getNames(): ?array
    {
        return ['Jim', 'Rohn'];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class RemoveNullable
{
    public function go()
    {
        $this->run($this->getNames());
    }

    /**
     * @param string[] $items
     */
    private function run(array $items)
    {
    }

    /**
     * @return string[]|null
     */
    private function getNames(): ?array
    {
        return ['Jim', 'Rohn'];
    }
}

?>
