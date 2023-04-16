<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class ConditionalAssignInConstructor
{
    private string $env;

    public function __construct(bool $sandbox)
    {
        if ($sandbox) {
            $this->env = 'sandbox';
        } else {
            $this->env = 'prod';
        }
    }
}

?>
