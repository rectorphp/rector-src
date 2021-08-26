<?php

namespace Rector\Tests\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector\Fixture;

abstract class ParentClass implements \SplObserver
{
    public function update(\SplSubject $subject): void
    {
    }
}

class IndirectImplementsNativeParamers extends ParentClass
{
    public function update($subject): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector\Fixture;

abstract class ParentClass implements \SplObserver
{
    /**
     * @param \SplSubject $subject
     */
    public function update($subject): void
    {
    }
}

class IndirectImplementsNativeParamers extends ParentClass
{
    public function update($subject): void
    {
    }
}

?>