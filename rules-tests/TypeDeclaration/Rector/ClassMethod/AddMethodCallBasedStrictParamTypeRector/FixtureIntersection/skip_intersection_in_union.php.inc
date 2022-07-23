<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\FixtureIntersection;

abstract class SkipIntersectionInUnion
{
    public function prepareParameters(): array
    {
        $parameters =  array_merge($this->getRequestParameters(), [
            'request' => $this->getRequestName(),
        ]);

        $parameters = $this->removeEmptyValues($parameters);

        return array_map(static fn ($parameter) => 'test', $parameters);
    }

    private function removeEmptyValues($input): array
    {
        foreach ($input as &$value) {
            if (! is_array($value)) {
                continue;
            }

            $value = $this->removeEmptyValues($value);
        }

        return array_filter($input, static fn ($val) => true);
    }
}