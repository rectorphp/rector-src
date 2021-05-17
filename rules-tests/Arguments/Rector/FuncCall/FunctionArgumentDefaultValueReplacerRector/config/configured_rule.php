<?php

declare(strict_types=1);

use Rector\Arguments\Rector\FuncCall\FunctionArgumentDefaultValueReplacerRector;
use Rector\Arguments\ValueObject\FuncCallArgumentDefaultValueReplacer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(Rector\Arguments\Rector\FuncCall\FunctionArgumentDefaultValueReplacerRector::class)
        ->call('configure', [[
            FunctionArgumentDefaultValueReplacerRector::REPLACED_ARGUMENTS => ValueObjectInliner::inline([
                new FuncCallArgumentDefaultValueReplacer('version_compare', 2, 'gte', 'ge'),
                new FuncCallArgumentDefaultValueReplacer('version_compare', 2, 'lte', 'le'),
                new FuncCallArgumentDefaultValueReplacer('version_compare', 2, '', '!='),
                new FuncCallArgumentDefaultValueReplacer('version_compare', 2, '!', '!='),
                new FuncCallArgumentDefaultValueReplacer('version_compare', 2, 'g', 'gt'),
                new FuncCallArgumentDefaultValueReplacer('version_compare', 2, 'l', 'lt'),
                new FuncCallArgumentDefaultValueReplacer('version_compare', 2, 'gte', 'ge'),
                new FuncCallArgumentDefaultValueReplacer('version_compare', 2, 'lte', 'le'),
                new FuncCallArgumentDefaultValueReplacer('version_compare', 2, 'n', 'ne'),
                new FuncCallArgumentDefaultValueReplacer(
                    'some_function',
                    0,
                    true,
                    'Symfony\Component\Yaml\Yaml::DUMP_EXCEPTION_ON_INVALID_TYPE'
                ),
            ]),
        ]]);
};
