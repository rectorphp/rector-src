<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class DoNotRemoveParameterAttribute
{
    private string $password;

    public function __construct(#[\SensitiveParameter] string $password)
    {
        $this->password = $password;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class DoNotRemoveParameterAttribute
{
    public function __construct(#[\SensitiveParameter]private string $password)
    {
    }
}

?>
