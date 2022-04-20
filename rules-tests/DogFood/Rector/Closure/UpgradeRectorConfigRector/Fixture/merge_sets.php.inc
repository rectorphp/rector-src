<?php

namespace Rector\Tests\DogFood\Rector\Closure\UpgradeRectorConfigRector\Fixture;

use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::PHP_71);
    $containerConfigurator->import(SetList::PHP_72);
};

?>
-----
<?php

namespace Rector\Tests\DogFood\Rector\Closure\UpgradeRectorConfigRector\Fixture;

use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (\Rector\Config\RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::PHP_71, SetList::PHP_72]);
};

?>
