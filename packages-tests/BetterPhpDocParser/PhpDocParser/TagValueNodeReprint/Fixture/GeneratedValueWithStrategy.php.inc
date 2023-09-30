<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\Fixture;

use Doctrine\ORM\Mapping as ORM;

class GeneratedValueWithStrategy
{
    /**
     * @ORM\GeneratedValue("AUTO")
     */
    private $implicit;

    /**
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $explicitWithoutQuotes;
}

?>
-----
PhpParser\Node\Stmt\Property
-----
Doctrine\ORM\Mapping\GeneratedValue
