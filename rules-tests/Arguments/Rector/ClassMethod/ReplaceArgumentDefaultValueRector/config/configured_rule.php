<?php

declare(strict_types=1);

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use Rector\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector;
use Rector\Arguments\ValueObject\ReplaceArgumentDefaultValue;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $falseConstant = new ConstFetch(new Name('false'));
    $trueConstant = new ConstFetch(new Name('true'));

    $services->set(ReplaceArgumentDefaultValueRector::class)
        ->call('configure', [[
            ReplaceArgumentDefaultValueRector::REPLACED_ARGUMENTS => ValueObjectInliner::inline([
                new ReplaceArgumentDefaultValue(
                    'Symfony\Component\DependencyInjection\Definition',
                    'setScope',
                    0,
                    new ClassConstFetch(new FullyQualified(
                        'Symfony\Component\DependencyInjection\ContainerBuilder'
                    ), 'SCOPE_PROTOTYPE'),
                    $falseConstant,
                ),
                new ReplaceArgumentDefaultValue(
                    'Symfony\Component\Yaml\Yaml',
                    'parse',
                    1,
                    new Array_([$falseConstant, $falseConstant, $trueConstant]),
                    new ClassConstFetch(new FullyQualified('Symfony\Component\Yaml\Yaml'), 'PARSE_OBJECT_FOR_MAP')
                ),

                new ReplaceArgumentDefaultValue('Symfony\Component\Yaml\Yaml', 'parse', 1, new Array_([
                    $falseConstant,
                    $trueConstant,
                ]), new ClassConstFetch(new FullyQualified('Symfony\Component\Yaml\Yaml'), 'PARSE_OBJECT')),

                new ReplaceArgumentDefaultValue('Symfony\Component\Yaml\Yaml', 'parse', 1, new ConstFetch(new Name(
                    'false'
                )), new LNumber(0)),

                new ReplaceArgumentDefaultValue(
                    'Symfony\Component\Yaml\Yaml',
                    'parse',
                    1,
                    $trueConstant,
                    new ClassConstFetch(new FullyQualified(
                        'Symfony\Component\Yaml\Yaml'
                    ), 'PARSE_EXCEPTION_ON_INVALID_TYPE')
                ),

                new ReplaceArgumentDefaultValue('Symfony\Component\Yaml\Yaml', 'dump', 3, new Array_([
                    $falseConstant,
                    $trueConstant,
                ]), new ClassConstFetch(new FullyQualified('Symfony\Component\Yaml\Yaml'), 'DUMP_OBJECT')),

                new ReplaceArgumentDefaultValue(
                    'Symfony\Component\Yaml\Yaml',
                    'dump',
                    3,
                    $trueConstant,
                    new ClassConstFetch(new FullyQualified(
                        'Symfony\Component\Yaml\Yaml'
                    ), 'DUMP_EXCEPTION_ON_INVALID_TYPE')
                ),

            ]),
        ]]);
};
