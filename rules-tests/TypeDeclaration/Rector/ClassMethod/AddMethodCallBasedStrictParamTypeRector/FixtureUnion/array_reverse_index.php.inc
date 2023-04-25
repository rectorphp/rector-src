<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\FixtureUnion;

final class ArrayReverseIndex
{
    public function run()
    {
        $parts = array_reverse(explode('/', '/some/test/url'));
        foreach ($parts as $index => $uri) {
            if ($index === 0) {
                continue;
            }

            $this->someOtherMethod($index, $uri);
        }
    }

    private function someOtherMethod(int $index, string $uri)
    {
        return sprintf('%d-%s', $index, $uri);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\FixtureUnion;

final class ArrayReverseIndex
{
    public function run()
    {
        $parts = array_reverse(explode('/', '/some/test/url'));
        foreach ($parts as $index => $uri) {
            if ($index === 0) {
                continue;
            }

            $this->someOtherMethod($index, $uri);
        }
    }

    private function someOtherMethod(int|string $index, string $uri)
    {
        return sprintf('%d-%s', $index, $uri);
    }
}

?>
