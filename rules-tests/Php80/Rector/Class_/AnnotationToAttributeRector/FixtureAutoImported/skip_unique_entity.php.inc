<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixtureAutoImported;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 */
#[Entity(repositoryClass: 'UserRepository')]
#[Table(name: 'core_user')]
class User
{
}

?>
