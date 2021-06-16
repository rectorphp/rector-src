<?php

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Mapping\Annotation as Gedmo;

#[Entity]
#[Table]
namespace Rector\Php80\Tests\Rector\Class_\AnnotationToAttributeRector\Fixture;

final class DemoFile
{
    /**
     * @Gedmo\SortablePosition
     */
    #[Column('position', type: 'integer')]
    protected int $position = 0;
}
?>
?>
