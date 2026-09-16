<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

final class StringFallback
{
    public function run(?\DateTimeImmutable $dateObso): string
    {
        return null !== $dateObso ? $dateObso->format('d/m/Y h:i:s') : '';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

final class StringFallback
{
    public function run(?\DateTimeImmutable $dateObso): string
    {
        return $dateObso?->format('d/m/Y h:i:s') ?? '';
    }
}

?>
