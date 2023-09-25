<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode\FixtureNested;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Mapping\Table(
 *      name="doctrine_entity",
 *      uniqueConstraints={@UniqueConstraint(name="property")}
 *  )
 */
class DoctrineEntity
{
    /**
     * @JoinTable(name="property",
     *      joinColumns={@JoinColumn(name="property", referencedColumnName="property")},
     *      inverseJoinColumns={@JoinColumn(name="property", referencedColumnName="property", unique=true)}
     * )
     */
    public $property;
}