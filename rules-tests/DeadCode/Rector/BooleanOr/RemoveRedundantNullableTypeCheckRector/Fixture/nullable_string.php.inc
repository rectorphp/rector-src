<?php

namespace Rector\Tests\DeadCode\Rector\BooleanOr\RemoveRedundantNullableTypeCheckRector\Fixture;

final class NullableString
{
    public function run(?string $securityExpression): void
    {
        if (null === $securityExpression || ! is_string($securityExpression)) {
            return;
        }

        echo $securityExpression;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\BooleanOr\RemoveRedundantNullableTypeCheckRector\Fixture;

final class NullableString
{
    public function run(?string $securityExpression): void
    {
        if (null === $securityExpression) {
            return;
        }

        echo $securityExpression;
    }
}

?>
