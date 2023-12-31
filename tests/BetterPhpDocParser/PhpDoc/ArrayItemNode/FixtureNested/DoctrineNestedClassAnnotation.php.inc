<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDoc\ArrayItemNode\FixtureNested;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *      name="doctrine_entity",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="property")}
 *  )
 */
class DoctrineEntity
{
    /**
     * @ORM\JoinTable(name="property",
     *      joinColumns={@ORM\JoinColumn(name="property", referencedColumnName="property")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="property", referencedColumnName="property", unique=true)}
     * )
     */
    public $property;
}
