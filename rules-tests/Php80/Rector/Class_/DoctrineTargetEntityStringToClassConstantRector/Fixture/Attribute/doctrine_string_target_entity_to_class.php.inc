<?php

namespace Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\Fixture\Attribute;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\Source\AnotherClass;

final class MyEntity
{
    #[ORM\Embedded(class: "AnotherClass")]
    private readonly ?Collection $other;
    #[ORM\OneToMany(targetEntity: "AnotherClass")]
    private readonly ?Collection $items;
    #[ORM\ManyToOne(targetEntity: "AnotherClass")]
    private readonly ?Collection $items2;
    #[ORM\ManyToMany(targetEntity: "AnotherClass")]
    private readonly ?Collection $items3;
    #[ORM\OneToOne(mappedBy: 'class', targetEntity: "AnotherClass")]
    private readonly ?Collection $items4;
    #[ORM\ManyToOne(targetEntity: "UnresolvableClass")]
    private readonly ?Collection $items5;
    #[ORM\ManyToOne(targetEntity: "App:AnotherClass")]
    private readonly ?Collection $items6;

    public function addItem(AnotherClass $myOtherEntity): void
    {
        $this->items->add($myOtherEntity);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\Fixture\Attribute;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\Source\AnotherClass;

final class MyEntity
{
    #[ORM\Embedded(class: \Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\Source\AnotherClass::class)]
    private readonly ?Collection $other;
    #[ORM\OneToMany(targetEntity: \Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\Source\AnotherClass::class)]
    private readonly ?Collection $items;
    #[ORM\ManyToOne(targetEntity: \Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\Source\AnotherClass::class)]
    private readonly ?Collection $items2;
    #[ORM\ManyToMany(targetEntity: \Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\Source\AnotherClass::class)]
    private readonly ?Collection $items3;
    #[ORM\OneToOne(mappedBy: 'class', targetEntity: \Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\Source\AnotherClass::class)]
    private readonly ?Collection $items4;
    #[ORM\ManyToOne(targetEntity: "UnresolvableClass")]
    private readonly ?Collection $items5;
    #[ORM\ManyToOne(targetEntity: "App:AnotherClass")]
    private readonly ?Collection $items6;

    public function addItem(AnotherClass $myOtherEntity): void
    {
        $this->items->add($myOtherEntity);
    }
}

?>
