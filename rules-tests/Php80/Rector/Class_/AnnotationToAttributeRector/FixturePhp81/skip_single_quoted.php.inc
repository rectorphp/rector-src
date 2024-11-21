<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

/**
 * @\Doctrine\ORM\Mapping\Entity()
 * @\Doctrine\ORM\Mapping\Table('user', indexes={
 *  @\Doctrine\ORM\Mapping\Index(name="name_index", columns={"name"}),
 *  @\Doctrine\ORM\Mapping\Index(name="surname_index", columns={"surname"}),
 * })
 */
class SingleQuote
{
}
