<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

class ManyToManyWithJoinTable
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Example", inversedBy="backLink")
     * @ORM\JoinTable(name="table_name",
     *     joinColumns={@ORM\JoinColumn(name="origin_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="target_id", referencedColumnName="id")}
     * )
     */
    private $collection;
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

class ManyToManyWithJoinTable
{
    #[ORM\ManyToMany(targetEntity: 'App\Entity\Example', inversedBy: 'backLink')]
    #[ORM\JoinTable(name: 'table_name')]
    #[ORM\JoinColumn(name: 'origin_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'target_id', referencedColumnName: 'id')]
    private $collection;
}

?>
