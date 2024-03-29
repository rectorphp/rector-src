<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\Fixture;

use Symfony\Component\Validator\Constraints as Assert;

class AssertQuoteChoice
{
    const CHOICE_ONE = 'choice_one';
    const CHOICE_TWO = 'choice_two';

    /**
     * @Assert\Choice({AssertQuoteChoice::CHOICE_ONE, AssertQuoteChoice::CHOICE_TWO})
     */
    private $someChoice = self::CHOICE_ONE;
}

?>
-----
PhpParser\Node\Stmt\Property
-----
Symfony\Component\Validator\Constraints\Choice
