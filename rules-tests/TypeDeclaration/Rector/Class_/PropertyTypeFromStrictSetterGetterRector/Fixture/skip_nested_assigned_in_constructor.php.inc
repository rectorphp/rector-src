<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class SkipNestedAssignedInConstructor
{
    private $listPath;

    public function __construct(string $listPath)
    {
        if ($listPath !== '') {
            $this->listPath = $listPath;
        }
    }

    public function getListPath(): string
    {
        return $this->listPath;
    }

    public function setListPath(string $path): void
    {
        $this->listPath = $path;
    }
}
