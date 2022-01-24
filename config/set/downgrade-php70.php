<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DowngradePhp70\Rector\ClassMethod\DowngradeParentTypeDeclarationRector;
use Rector\DowngradePhp70\Rector\ClassMethod\DowngradeSelfTypeDeclarationRector;
use Rector\DowngradePhp70\Rector\Coalesce\DowngradeNullCoalesceRector;
use Rector\DowngradePhp70\Rector\Declare_\DowngradeStrictTypeDeclarationRector;
use Rector\DowngradePhp70\Rector\Expr\DowngradeUnnecessarilyParenthesizedExpressionRector;
use Rector\DowngradePhp70\Rector\Expression\DowngradeDefineArrayConstantRector;
use Rector\DowngradePhp70\Rector\FuncCall\DowngradeDirnameLevelsRector;
use Rector\DowngradePhp70\Rector\FuncCall\DowngradeSessionStartArrayOptionsRector;
use Rector\DowngradePhp70\Rector\FuncCall\DowngradeUncallableValueCallToCallUserFuncRector;
use Rector\DowngradePhp70\Rector\FunctionLike\DowngradeScalarTypeDeclarationRector;
use Rector\DowngradePhp70\Rector\FunctionLike\DowngradeThrowableTypeDeclarationRector;
use Rector\DowngradePhp70\Rector\GroupUse\SplitGroupedUseImportsRector;
use Rector\DowngradePhp70\Rector\Instanceof_\DowngradeInstanceofThrowableRector;
use Rector\DowngradePhp70\Rector\MethodCall\DowngradeClosureCallRector;
use Rector\DowngradePhp70\Rector\MethodCall\DowngradeMethodCallOnCloneRector;
use Rector\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector;
use Rector\DowngradePhp70\Rector\Spaceship\DowngradeSpaceshipRector;
use Rector\DowngradePhp70\Rector\String_\DowngradeGeneratedScalarTypesRector;
use Rector\DowngradePhp70\Rector\TryCatch\DowngradeCatchThrowableRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_56);

    $services = $containerConfigurator->services();
    $services->set(DowngradeCatchThrowableRector::class);
    $services->set(DowngradeInstanceofThrowableRector::class);
    $services->set(DowngradeScalarTypeDeclarationRector::class);
    $services->set(DowngradeThrowableTypeDeclarationRector::class);
    $services->set(DowngradeStrictTypeDeclarationRector::class);
    $services->set(DowngradeSelfTypeDeclarationRector::class);
    $services->set(DowngradeAnonymousClassRector::class);
    $services->set(DowngradeNullCoalesceRector::class);
    $services->set(DowngradeSpaceshipRector::class);
    $services->set(DowngradeDefineArrayConstantRector::class);
    $services->set(DowngradeDirnameLevelsRector::class);
    $services->set(DowngradeSessionStartArrayOptionsRector::class);
    $services->set(DowngradeUncallableValueCallToCallUserFuncRector::class);
    $services->set(SplitGroupedUseImportsRector::class);
    $services->set(DowngradeClosureCallRector::class);
    $services->set(DowngradeGeneratedScalarTypesRector::class);
    $services->set(DowngradeParentTypeDeclarationRector::class);
    $services->set(DowngradeMethodCallOnCloneRector::class);
    $services->set(DowngradeUnnecessarilyParenthesizedExpressionRector::class);
};
