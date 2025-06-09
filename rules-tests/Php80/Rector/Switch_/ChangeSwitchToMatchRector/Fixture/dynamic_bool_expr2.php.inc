<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class DynamicBoolExpr2
{
    public static function boolean(callable $filter): void
    {
        switch ($filter) {
            case $filter instanceof \Closure:
                $reflection = new \ReflectionFunction($filter);
                break;
            case is_object($filter) && method_exists($filter, '__invoke'):
                $reflection = new \ReflectionMethod($filter, '__invoke');
                break;
            default:
                throw new \InvalidArgumentException(
                    sprintf('Expected Closure or invokable object on callable filter, %s given', gettype($filter))
                );
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class DynamicBoolExpr2
{
    public static function boolean(callable $filter): void
    {
        $reflection = match (true) {
            $filter instanceof \Closure => new \ReflectionFunction($filter),
            is_object($filter) && method_exists($filter, '__invoke') => new \ReflectionMethod($filter, '__invoke'),
            default => throw new \InvalidArgumentException(
                sprintf('Expected Closure or invokable object on callable filter, %s given', gettype($filter))
            ),
        };
    }
}

?>
