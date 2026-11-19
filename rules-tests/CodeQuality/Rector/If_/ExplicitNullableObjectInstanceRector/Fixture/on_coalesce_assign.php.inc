<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

class OnCoalesceAssign
{
    public function run(?\DateTimeImmutable $param, ?\DateTimeImmutable $fallback)
    {
        if (! ($param ??= $fallback)) {
            return 'no';
        }

        return $param;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

class OnCoalesceAssign
{
    public function run(?\DateTimeImmutable $param, ?\DateTimeImmutable $fallback)
    {
        if (!($param ??= $fallback) instanceof \DateTimeImmutable) {
            return 'no';
        }

        return $param;
    }
}

?>
