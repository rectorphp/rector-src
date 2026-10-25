<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector;
use Rector\TypeDeclaration\Rector\Class_\ReturnTypeFromMockObjectRector;
use Rector\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector;
use Rector\Visibility\Rector\Class_\ChangeMethodVisibilityRector;

// backward-compatibility aliases for rules retargeted to Class_/ClassLike nodes and moved out of the ClassMethod namespace,
// so configs and 3rd party sets referencing the former namespace keep working
$ruleClassAliases = [
    ChangeMethodVisibilityRector::class => 'Rector\Visibility\Rector\ClassMethod\ChangeMethodVisibilityRector',
    RemoveParentDelegatingClassMethodRector::class => 'Rector\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector',
    ReturnTypeFromMockObjectRector::class => 'Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromMockObjectRector',
    AddVoidReturnTypeWhereNoReturnRector::class => 'Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector',
];

foreach ($ruleClassAliases as $currentClass => $legacyClass) {
    class_alias($currentClass, $legacyClass);
}
