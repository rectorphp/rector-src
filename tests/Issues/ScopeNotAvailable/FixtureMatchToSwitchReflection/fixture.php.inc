<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\FixtureMatchToSwitchReflection;

final class ServiceValueResolver
{
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        try {

        } catch (\RuntimeException $e) {

            $r = new \ReflectionProperty($e, 'message');
            $r->setValue($e, $message);

            throw $e;
        }
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\FixtureMatchToSwitchReflection;

final class ServiceValueResolver
{
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        try {

        } catch (\RuntimeException $e) {

            $r = new \ReflectionProperty($e, 'message');
            $r->setAccessible(true);

            throw $e;
        }
    }
}

?>
