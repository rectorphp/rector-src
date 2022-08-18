<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

final class MultipleInversedJoinColumns
{
    /**
     * @ORM\JoinTable(name="join_table_name",
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="target_id"),
     *          @ORM\JoinColumn(name="another_id")
     *     }
     * )
     */
    private $collection;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

final class MultipleInversedJoinColumns
{
    #[ORM\JoinTable(name: 'join_table_name')]
    #[ORM\InverseJoinColumn(name: 'target_id')]
    #[ORM\InverseJoinColumn(name: 'another_id')]
    private $collection;
}

?>
