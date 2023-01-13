<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\Entity\SomeAnswerEntity;

#[Entity]
#[Table]
class SkipAnotherDoctrineCollection
{
    /**
     * @var Collection<int, SomeAnswerEntity>
     */
    #[OneToMany(mappedBy: 'user', targetEntity: SomeAnswerEntity::class)]
    private Collection $someAnswers;

    public function __construct(
    ) {
        $this->someAnswers = new ArrayCollection();
    }
}
