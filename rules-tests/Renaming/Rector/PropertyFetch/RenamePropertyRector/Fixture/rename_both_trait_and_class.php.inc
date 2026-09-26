<?php

namespace Rector\Tests\Renaming\Rector\PropertyFetch\RenamePropertyRector\Fixture;

/**
 * Both trait and class here on purpose to show that the property being renamed in both trait and class
 */
trait InstanceConfigTrait
{
    protected array $config = [];

    public function initCfg(): void
    {
       $this->config = $this->defaultConfig;
    }
}

final class UseTraitInClass
{
    use InstanceConfigTrait;

    protected array $defaultConfig = [
        'fields' => [],
    ];
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\PropertyFetch\RenamePropertyRector\Fixture;

/**
 * Both trait and class here on purpose to show that the property being renamed in both trait and class
 */
trait InstanceConfigTrait
{
    protected array $config = [];

    public function initCfg(): void
    {
       $this->config = $this->defaultConfigNew;
    }
}

final class UseTraitInClass
{
    use InstanceConfigTrait;

    protected array $defaultConfigNew = [
        'fields' => [],
    ];
}

?>