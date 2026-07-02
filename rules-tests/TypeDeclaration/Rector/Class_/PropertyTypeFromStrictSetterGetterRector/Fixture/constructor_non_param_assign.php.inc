<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Source\SomeConfigHelper;

final class ConstructorNonParamAssign
{
    private $listPath;

    public function __construct(SomeConfigHelper $configHelper)
    {
        $this->listPath = $configHelper->get('path', '');
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

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Source\SomeConfigHelper;

final class ConstructorNonParamAssign
{
    private string $listPath;

    public function __construct(SomeConfigHelper $configHelper)
    {
        $this->listPath = $configHelper->get('path', '');
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

?>
