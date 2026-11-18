<?php

namespace Rector\Tests\Issues\ClassAttributeStringClassName\Fixture;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\DiscriminatorMap({"page" = "Rector\Tests\Issues\ClassAttributeStringClassName\Source\Page"})
 */
class Statistic
{
}

?>
-----
<?php

namespace Rector\Tests\Issues\ClassAttributeStringClassName\Fixture;

use Doctrine\ORM\Mapping as ORM;

#[ORM\DiscriminatorMap(['page' => \Rector\Tests\Issues\ClassAttributeStringClassName\Source\Page::class])]
class Statistic
{
}

?>
