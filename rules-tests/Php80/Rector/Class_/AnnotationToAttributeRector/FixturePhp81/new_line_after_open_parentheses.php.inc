<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

/**
 * @\Doctrine\ORM\Mapping\Table(
 *      uniqueConstraints={
 *          @\Doctrine\ORM\Mapping\UniqueConstraint(name="some_key")
 *      }
 * )
 */
final class AfterOpenParentheses
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;

#[\Doctrine\ORM\Mapping\Table]
#[\Doctrine\ORM\Mapping\UniqueConstraint(name: 'some_key')]
final class AfterOpenParentheses
{
}

?>
