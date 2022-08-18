<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

class SomeEntity
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Example", inversedBy="backLink")
     * @ORM\JoinTable(name="join_table_name",
     *     joinColumns={@ORM\JoinColumn(name="origin_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="target_id", referencedColumnName="id")}
     * )
     */
    private $collection;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

class SomeEntity
{
    #[ORM\ManyToMany(targetEntity: 'App\Entity\Example', inversedBy: 'backLink')]
    #[ORM\JoinTable(name: 'join_table_name')]
    #[ORM\JoinColumn(name: 'origin_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'target_id', referencedColumnName: 'id')]
    private $collection;
}

?>
