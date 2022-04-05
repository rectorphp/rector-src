<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

final class SkipConditionallyChangedPropertyDefaultValue
{
    /**
     * @var string
     */
    private $rootView = 'admin.index';

    public function __construct()
    {
        if (Str::startsWith(request()->getRequestUri(), '/super-admin')) {
            $this->rootView = 'super_admin.index';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

final class SkipConditionallyChangedPropertyDefaultValue
{
    private string $rootView = 'admin.index';

    public function __construct()
    {
        if (Str::startsWith(request()->getRequestUri(), '/super-admin')) {
            $this->rootView = 'super_admin.index';
        }
    }
}

?>
