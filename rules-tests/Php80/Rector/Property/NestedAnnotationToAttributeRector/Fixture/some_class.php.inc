<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

final class SomeEntity
{
    /**
     * @ORM\JoinTable(name="join_table_name",
     *     joinColumns={@ORM\JoinColumn(name="origin_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="target_id")}
     * )
     */
    private $collection;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

final class SomeEntity
{
    #[ORM\JoinTable(name: 'join_table_name')]
    #[ORM\JoinColumn(name: 'origin_id')]
    #[ORM\InverseJoinColumn(name: 'target_id')]
    private $collection;
}

?>
