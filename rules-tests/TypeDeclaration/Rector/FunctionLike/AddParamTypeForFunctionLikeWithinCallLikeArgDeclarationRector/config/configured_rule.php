<?php

declare(strict_types=1);

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use Rector\Config\RectorConfig;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeForFunctionLikeWithinCallLikeArgDeclaration;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector::class, [
            new AddParamTypeForFunctionLikeWithinCallLikeArgDeclaration(
                'SomeNamespace\SomeClass',
                'someCall',
                0,
                0,
                new StringType()
            ),
            new AddParamTypeForFunctionLikeWithinCallLikeArgDeclaration(
                'SomeNamespace\SomeClassForNamed',
                'someCall',
                'callback',
                0,
                new StringType()
            ),
            new AddParamTypeForFunctionLikeWithinCallLikeArgDeclaration(
                'SomeNamespace\SomeClass',
                'someDynamicCall',
                1,
                0,
                function (array $args): ?ObjectType {
                    if ($args === [] || ! $args[0] instanceof Arg) {
                        return null;
                    }

                    $classConst = $args[0]->value;

                    if (
                        $classConst instanceof ClassConstFetch &&
                        $classConst->name instanceof Identifier &&
                        $classConst->class instanceof Name &&
                        $classConst->name->name === 'class') {
                        return new ObjectType($classConst->class->toString());
                    }

                    return null;
                },
            ),
            new AddParamTypeForFunctionLikeWithinCallLikeArgDeclaration(
                'SomeNamespace\SomeClass',
                'someDynamicCallWithVar',
                1,
                0,
                function (array $args, NodeTypeResolver $nodeTypeResolver): ?Type {
                    if ($args === [] || ! $args[0] instanceof Arg) {
                        return null;
                    }

                    $var = $args[0]->value;

                    return $nodeTypeResolver->getType($var);
                },
            ),
        ]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
