<?php

namespace App;

class Foo {}

class NamespaceEqualPrefixClassString
{
    public function hasMethod($classReflection, string $methodName): bool
    {
        if ($classReflection->getName() !== 'App\Foo') {
            return false;
        }

        if (! str_starts_with($methodName, 'with')) {
            return false;
        }

        return true;
    }
}

?>
-----
<?php

namespace App;

class Foo {}

class NamespaceEqualPrefixClassString
{
    public function hasMethod($classReflection, string $methodName): bool
    {
        if ($classReflection->getName() !== \App\Foo::class) {
            return false;
        }
        return str_starts_with($methodName, 'with');
    }
}

?>