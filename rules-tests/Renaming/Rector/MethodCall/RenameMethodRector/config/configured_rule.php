<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\MethodCallRenameWithArrayKey;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture\RenameTraitMethodCall;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture\RenameTraitMethod;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\AbstractType;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\CustomType;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\DifferentInterface;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\Enum\SomeEnumWithMethod;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\Foo;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\NewInterface;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\SomeSubscriber;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\SomeTrait;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename(AbstractType::class, 'setDefaultOptions', 'configureOptions'),
        new MethodCallRename('Nette\Utils\Html', 'add', 'addHtml'),
        new MethodCallRename(CustomType::class, 'notify', '__invoke'),
        new MethodCallRename(
            \Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture\CustomType::class,
            'notify',
            '__invoke'
        ),
        new MethodCallRename(SomeSubscriber::class, 'old', 'new'),
        new MethodCallRename(Foo::class, 'old', 'new'),
        new MethodCallRename(NewInterface::class, 'some_old', 'some_new'),
        new MethodCallRename(DifferentInterface::class, 'renameMe', 'toNewVersion'),

        new MethodCallRename(ReflectionFunctionAbstract::class, 'getTentativeReturnType', 'getReturnType'),

        new MethodCallRenameWithArrayKey('Nette\Utils\Html', 'addToArray', 'addToHtmlArray', 'hey'),
        new MethodCallRename('Symfony\\Component\\Workflow\\DefinitionBuilder', 'reset', 'clear'),

        new MethodCallRename(SomeEnumWithMethod::class, 'oldEnumMethod', 'newEnumMethod'),
        new MethodCallRename(SomeTrait::class, '_test', 'test'),
        new MethodCallRename(RenameTraitMethod::class, 'run', 'execute'),
        new MethodCallRename(RenameTraitMethodCall::class, 'method1', 'method3'),
    ]);
};
