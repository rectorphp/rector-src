<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

final class DoctrineJoinColumnNestedInJoinTable
{
    /**
     * @JoinTable(name="lemma_type",
     *      inverseJoinColumns={@JoinColumn(name="lemma_id", referencedColumnName="lemma_id")}
     * )
     */
    private iterable $lemmas;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

final class DoctrineJoinColumnNestedInJoinTable
{
    #[JoinTable(name: 'lemma_type')]
    #[InverseJoinColumn(name: 'lemma_id', referencedColumnName: 'lemma_id')]
    private iterable $lemmas;
}

?>
