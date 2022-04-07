<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\UseRectorContainerConfiguratorRector\Fixture;

return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator): void {
};

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\UseRectorContainerConfiguratorRector\Fixture;

return static function (\Rector\Core\Config\RectorContainerConfigurator $containerConfigurator): void {
};

?>
