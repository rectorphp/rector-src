<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class SkipArrayReverseIndex
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
