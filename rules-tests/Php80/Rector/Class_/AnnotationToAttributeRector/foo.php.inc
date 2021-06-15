<?php

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

#[Entity(SortableRepository::class)]
final class DemoFile
{
    /**
     * @Gedmo\SortablePosition
     */
    #[Column('position', type: 'integer')]
    protected int $position = 0;
}
?>
