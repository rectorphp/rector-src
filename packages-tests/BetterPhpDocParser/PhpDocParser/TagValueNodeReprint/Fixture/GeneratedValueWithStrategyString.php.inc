<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\Fixture;

use Doctrine\ORM\Mapping as ORM;

class GeneratedValueWithStrategyString
{
    /**
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $explicit;
}

?>
-----
PhpParser\Node\Stmt\Property
-----
Doctrine\ORM\Mapping\GeneratedValue
