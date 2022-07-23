<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\FixtureIntersection;

abstract class SkipIntersectionInUnion
{
    public function prepareParameters(): array
    {
        $parameters =  array_merge($this->getRequestParameters(), [
            'request' => $this->getRequestName(),
        ]);

        $this->removeEmptyValues($parameters);

        return [];
    }

    private function removeEmptyValues($input): array
    {
        foreach ($input as &$value) {
            if (! is_array($value)) {
                continue;
            }

            $value = $this->removeEmptyValues($value);
        }

        return [];
    }
}