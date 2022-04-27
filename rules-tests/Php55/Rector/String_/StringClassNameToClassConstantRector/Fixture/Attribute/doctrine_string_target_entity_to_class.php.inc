<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\Attribute;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass;

final class MyEntity
{
    #[ORM\ManyToOne(targetEntity: "AnotherClass")]
    private readonly ?Collection $items;

    public function addItem(AnotherClass $myOtherEntity): void
    {
        $this->items->add($myOtherEntity);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\Attribute;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass;

final class MyEntity
{
    #[ORM\ManyToOne(targetEntity: AnotherClass::class)]
    private readonly ?Collection $items;

    public function addItem(AnotherClass $myOtherEntity): void
    {
        $this->items->add($myOtherEntity);
    }
}

?>
