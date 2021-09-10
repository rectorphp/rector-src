<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssueConstructorPromotionRename\Fixture;

final class Fixture
{
    private $a;
    public function __construct(\DateTimeInterface $a)
    {
        $this->a = $a;
        dump($a);
        dump($a->format('now'));
    }
}

?>
