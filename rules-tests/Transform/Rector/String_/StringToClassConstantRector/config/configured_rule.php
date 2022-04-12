<?php

declare(strict_types=1);

use Rector\Transform\Rector\String_\StringToClassConstantRector;
use Rector\Transform\ValueObject\StringToClassConstant;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(StringToClassConstantRector::class)
        ->configure([

            new StringToClassConstant('compiler.post_dump', 'Yet\AnotherClass', 'CONSTANT'),
            new StringToClassConstant('compiler.to_class', 'Yet\AnotherClass', 'class'),

        ]);
};
