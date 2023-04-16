<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class MissingElseIfAssignInConstructor
{
    private string $env;

    public function __construct(bool $sandbox)
    {
        if ($sandbox) {
            $this->env = 'sandbox';
        } elseif (rand(0,1)) {
            $this->env = 'stage';
        } elseif (rand(0,1)) {
            // branch which does not assign to $this->env
        } else {
            $this->env = 'prod';
        }
    }
}

?>
