<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table('user', indexes={
 *  @ORM\Index(name="name_index", columns={"name"}),
 *  @ORM\Index(name="surname_index", columns={"surname"}),
 * })
 */
class SingleQuoteAlias
{
}
