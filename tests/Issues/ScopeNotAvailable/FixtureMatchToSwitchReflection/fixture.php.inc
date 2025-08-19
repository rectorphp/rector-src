<?php

namespace Rector\Tests\Issues\ScopeNotAvailable\FixtureMatchToSwitchReflection;

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

namespace Rector\Tests\Issues\ScopeNotAvailable\FixtureMatchToSwitchReflection;

final class ServiceValueResolver
{
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        try {

        } catch (\RuntimeException $e) {

            $r = new \ReflectionProperty($e, 'message');
            if (PHP_VERSION_ID < 80100) {
                $r->setAccessible(true);
            }
            $r->setValue($e, $message);

            throw $e;
        }
    }
}

?>
